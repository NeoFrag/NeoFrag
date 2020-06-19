<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Debug extends Core
{
	const INFO       = 0;
	const WARNING    = 1;
	const ERROR      = 2;
	const NOTICE     = 3;
	const DEPRECATED = 4;
	const STRICT     = 5;

	protected $_logs = [];
	private $_timeline = [];

	public function __construct($config = [])
	{
		ini_set('display_errors', FALSE);

		$this('Start');

		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext){
			if (error_reporting() !== 0)
			{
				if (NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
				{
					if (in_array($errno, [E_USER_ERROR, E_RECOVERABLE_ERROR]))
					{
						$error = 'error';
					}
					else if (in_array($errno, [E_USER_WARNING, E_WARNING]))
					{
						$error = 'warning';
					}
					else if (in_array($errno, [E_USER_NOTICE, E_NOTICE]))
					{
						$error = 'notice';
					}
					else if (in_array($errno, [E_DEPRECATED]))
					{
						$error = 'deprecated';
					}
					else if ($errno == E_STRICT)
					{
						$error = 'strict';
					}

					$this->_logs[] = [[], $errstr, $error, relative_path($errfile), $errline, $this->date(), memory_get_usage()];
				}
				else
				{
					return FALSE;
				}
			}
		});

		if (NEOFRAG_LOGS)
		{
			$this->on('output_rendered', function(){
				$cols = $lines = [];

				foreach ($this->_logs as list($args, $message, $type, $file, $line, $date, $memory))
				{
					array_unshift($args, $date->format('Y-m-d H:i:s.u'), sprintf('%.3f', ($memory - NEOFRAG_MEMORY) / 1024 / 1024).'Mb', strtoupper($type));

					foreach ($args as $i => $value)
					{
						$n = strlen($value);
						$cols[$i] = isset($cols[$i]) ? max($n, $cols[$i]) : $n;
					}

					if ($file)
					{
						$message .= ' '.$file.' '.$line;
					}

					$lines[] = [$args, $message];
				}

				dir_create('logs');

				if ($f = fopen('logs/neofrag.log', 'a'))
				{
					while (!flock($f, LOCK_EX));

					foreach ($lines as list($args, $message))
					{
						foreach ($cols as $i => $size)
						{
							$args[$i] = isset($args[$i]) ? sprintf('%'.$size.'.s', $args[$i]) : str_repeat(' ', $size);
						}

						fwrite($f, implode('    ', $args).' '.$message."\n");
					}

					fwrite($f, str_repeat('=', 350)."\n");

					flock($f, LOCK_UN);

					fclose($f);
				}
			});
		}
	}

	public function __invoke($message)
	{
		$memory = memory_get_usage();

		$args = func_get_args();
		$message = array_pop($args);

		$this->_logs[] = [$args, $message, 'info', '', 0, $this->date(), $memory];
	}

	public function timeline()
	{
		if (!func_num_args())
		{
			$output = '<table class="table table-striped">
						<tbody>';

			usort($this->_timeline, function($a, $b){
				return $a[1] > $b[1];
			});

			foreach ($this->_timeline as $object)
			{
				list($time) = $object->__debug->time;

				if (!isset($min, $max))
				{
					$min = $time[1];
					$max = $time[2];
				}
				else
				{
					$min = min($min, $time[1]);
					$max = max($max, $time[2]);
				}
			}

			$total = $max - $min;

			foreach ($this->_timeline as $time)
			{
				$class = 'float-left';

				if (preg_match('/class="(.*?)"/', $time[0], $match))
				{
					$class .= ' '.$match[1];
				}

				$output .= '	<tr>
									<td class="col-1">'.$time[0].'</td>
									<td>
										<div class="float-left" style="height: 25px; width: '.str_replace(',', '.', floor(($time[1] - $this->_timeline[0][1]) * 100 / $total)).'%;"></div>
										<div class="'.$class.'" style="height: 25px; display: block; padding: 0; width: '.str_replace(',', '.', max(1, floor(($time[2] - $time[1]) * 100 / $total))).'%;"></div>
									</td>
								</tr>';
			}

			$output .= '	</tbody>
						</table>';

			return $output;
		}
		else
		{
			$this->_timeline[] = func_get_args();
			return $this;
		}
	}

	public function bar($type = '', $data = NULL)
	{
		if (NEOFRAG_DEBUG_BAR)
		{
			static $debug_bar = [];

			if ($type && $data)
			{
				$debug_bar[$type] = $data;
				return $this;
			}
			else
			{
				$table = function($data) use (&$table){
					if (is_array($data) || (is_object($data) && method_exists($value, '__toString')))
					{
						$output = '<table class="table table-striped">';

						$data = (array)$data;
						ksort($data);

						foreach ($data as $key => $value)
						{
							$output .= '	<tr>
												<td style="width: 200px;"><b>'.$key.'</b></td>
												<td>'.$table($value).'</td>
											</tr>';
						}

						$output .= '</table>';

						return $output;
					}
					else if (is_bool($data))
					{
						return $data ? '<i class="fas fa-check text-success" title="TRUE"></i>' : '<i class="fas fa-times text-danger" title="FALSE"></i>';
					}
					else if ($data === NULL)
					{
						return '<em>NULL</em>';
					}
					else
					{
						return utf8_htmlentities(str_replace(["\n", "\r"], '', $data));
					}
				};

				$this	->bar('console', function(&$label){
							$result = '<table class="table table-striped">';

							$warning = $error = $notice = $deprecated = $strict = 0;

							foreach ($this->_logs as $i => list($prefix, $text, $type, $file, $line, $date))
							{
								if ($type == self::INFO)
								{
									$type = '<span class="badge badge-success">Info</span>';
								}
								else if ($type == self::WARNING)
								{
									$type = '<span class="badge badge-warning">Warning</span>';
									$warning++;
								}
								else if ($type == self::ERROR)
								{
									$type = '<span class="badge badge-danger">Error</span>';
									$error++;
								}
								else if ($type == self::NOTICE)
								{
									$type = '<span class="badge badge-info">Notice</span>';
									$notice++;
								}
								else if ($type == self::DEPRECATED)
								{
									$type = '<span class="badge badge-warning">Deprecated</span>';
									$deprecated++;
								}
								else if ($type == self::STRICT)
								{
									$type = '<span class="badge badge-secondary">Strict</span>';
									$strict++;
								}

								$result .= '	<tr>
													<td class="col-1"><b>'.($i + 1).'</b><div class="float-right">'.$type.'</div></td>
													<td class="col-8">'.utf8_htmlentities($text).'</td>
													<td class="col-3 text-right">'.$file.' <code>'.$line.'</code></td>
												</tr>';
							}

							$result .= '</table>';

							if ($error)
							{
								$label = '<span class="badge badge-danger">'.$error.'</span>';
							}
							else if ($warning)
							{
								$label = '<span class="badge badge-warning">'.$warning.'</span>';
							}
							else if ($strict)
							{
								$label = '<span class="badge badge-secondary">'.$strict.'</span>';
							}
							else if ($notice)
							{
								$label = '<span class="badge badge-info">'.$notice.'</span>';
							}
							else if ($deprecated)
							{
								$label = '<span class="badge badge-warning">'.$deprecated.'</span>';
							}

							return $result;
						})
						->bar('loader', function(){
							return '';
						})
						->bar('timeline', function(){
							return $this->timeline();
						})
						->bar('server', function(){
							return $_SERVER;
						});

				$this	->css('fonts/open-sans')
						->css('debug-bar')
						->js('debug-bar');

				$tabs = [
					'console'  => ['Console',  'fas fa-terminal'],
					'database' => ['Database', 'fas fa-database'],
					'loader'   => ['Loader',   'fas fa-puzzle-piece'],
					'timeline' => ['Timeline', 'far fa-clock'],
					'request'  => ['Request',  'far fa-hand-pointer'],
					'output'   => ['Result',   'fas fa-share'],
					'settings' => ['Settings', 'fas fa-cogs'],
					'session'  => ['Session',  'fas fa-flag'],
					'server'   => ['Server',   'fas fa-server']
				];

				array_walk($tabs, function(&$a, $name) use ($debug_bar, &$table){
					$label = NULL;

					if (is_array($result = $debug_bar[$name]($label)))
					{
						$result = $table($result);
					}

					$a[] = $result;

					if ($label)
					{
						$a[] = $label;
					}
				});

				return $this->view('debug/bar', [
					'tabs'   => $tabs,
					'active' => ($tab = $this->session('debug', 'tab')) && isset($tabs[$tab]) ? $tab : NULL
				]);
			}
		}
	}
}
