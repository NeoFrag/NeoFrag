<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Profiler extends Core
{
	const INFO    = 0;
	const WARNING = 1;
	const ERROR   = 2;

	private $_log = array();

	public function __construct()
	{
		parent::__construct();

		$this->log('URL : http://'.$_SERVER['HTTP_HOST'].rawurldecode($_SERVER['REQUEST_URI']), self::INFO);
		$this->log('IP : '.((isset($_SERVER['HTTP_X_REAL_IP'])) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR']), self::INFO);
	}

	public function __destruct()
	{
		$this->log('Temps écoulé depuis la requète HTTP : '.round((microtime(TRUE) - $_SERVER['REQUEST_TIME']) * 1000, 3).' ms', self::INFO);
		$this->log('Temps total d\'exécution : '.round((microtime(TRUE) - NEOFRAG_TIME) * 1000, 3).' ms', self::INFO);
		$this->log('Espace mémoire alloué par NeoFrag : '.round((memory_get_peak_usage(TRUE) - NEOFRAG_MEMORY) / 1024 / 1024, 3).' Mo', self::INFO);
		$this->log('Espace mémoire alloué par PHP : '.round(memory_get_peak_usage(TRUE) / 1024 / 1024, 3).' Mo', self::INFO);
		
		if (!is_asset() && file_exists(NEOFRAG_CMS.'/logs/'))
		{
			$f = fopen(NEOFRAG_CMS.'/logs/log.php', 'a');
			
			foreach ($this->_log as $log)
			{
				fwrite($f, timetostr('%Y-%m-%d %H:%M:%S').' : '.$log[0]."\n");
			}
			
			fwrite($f, str_repeat('=', 150)."\n");
			
			fclose($f);
		}
	}

	public function log($error, $type, $trace = 0)
	{
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $trace + 1);
		$this->_log[] = array($error, $type, relative_path($backtrace[$trace]['file']), $backtrace[$trace]['line']);
	}

	public function table($data)
	{
		if (is_array($data) || is_object($data))
		{
			$output = '<table class="table table-striped">
						<tbody>';

			foreach ((array)$data as $key => $value)
			{
				$output .= '		<tr>
										<td style="width: 200px;"><b>'.$key.'</b></td>
										<td>'.$this->table($value).'</td>
									</tr>';
			}

			$output .= '		</tbody>
							</table>';

			return $output;
		}
		else
		{
			if (is_bool($data))
			{
				return $data ? '<i class="fa fa-check text-success" title="TRUE"></i>' : '<i class="fa fa-close text-danger" title="FALSE"></i>';
			}
			else if (is_null($data))
			{
				return '<em>NULL</em>';
			}
			else
			{
				return utf8_htmlentities(str_replace(array("\n", "\r"), '', $data));
			}
		}
	}

	public function output()
	{
		if ($this->config->nf_debug == 2)
		{
			$this->log('Profileur généré', self::INFO);

			$output = '	<a href="#" data-profiler="all"><i class="icon-'.(($this->session('profiler', 'all')) ? 'plus' : 'remove').' pull-right"></i></a>
						<div class="profiler-block">
							<a href="#" data-profiler="performence"><i class="icon-chevron-'.(($this->session('profiler', 'performence')) ? 'down' : 'up').' pull-right"></i></a>
							<h2>Performance</h2>
							<div class="profiler-block">
								<table class="table table-striped">
									<tbody>
										<tr>
											<td style="width: 200px;"><b>Time since HTTP REQUEST</b></td>
											<td>'.round((microtime(TRUE) - $_SERVER['REQUEST_TIME']) * 1000, 3).' ms</td>
										</tr>
										<tr>
											<td style="width: 200px;"><b>Total execution time</b></td>
											<td>'.round((microtime(TRUE) - NEOFRAG_TIME) * 1000, 3).' ms</td>
										</tr>
										<tr>
											<td style="width: 200px;"><b>Memory allocated by NeoFrag</b></td>
											<td>'.round((memory_get_peak_usage(TRUE) - NEOFRAG_MEMORY) / 1024 / 1024, 3).' Mo</td>
										</tr>
										<tr>
											<td style="width: 200px;"><b>Memory allocated by PHP</b></td>
											<td>'.round(memory_get_peak_usage(TRUE) / 1024 / 1024, 3).' MB</td>
										</tr>
									</tbody>
								</table>
							</div>';

			$output .= '<a href="#" data-profiler="librairies"><i class="icon-chevron-'.(($this->session('profiler', 'librairies')) ? 'down' : 'up').' pull-right"></i></a>
						<h2>Loader</h2>
						<div style="overflow: hidden" class="profiler-block">'.$this->load->profiler('core').'</div>';

			foreach ($this->load->libraries as $library)
			{
				$output .= $library->profiler();
			}

			ksort($GLOBALS);

			foreach ($GLOBALS as $key => $value)
			{
				if (in_array($key, array('_COOKIE', '_ENV', '_GET', '_POST', '_SERVER')) && !empty($value))
				{
					$name = strtolower(trim($key, '_'));

					if ($name == 'cookie')
					{
						$name = 'cookies';
					}

					$output .= '<a href="#" data-profiler="'.$name.'"><i class="icon-chevron-'.(($this->session('profiler', $name)) ? 'down' : 'up').' pull-right"></i></a>
								<h2>'.ucfirst($name).'</h2>
								<div class="profiler-block">'.$this->table($value).'</div>';
				}
			}

			$output .= '</div>';
			
			return display(
				new Row(
					new Col(
						new Panel(array(
							'title'   => 'Profiler',
							'icon'    => 'fa-rocket',
							'content' => $output
						))
					),
					'row-default'
				)
			);
		}
		else if ($this->config->nf_debug == 1 && $this->error->has_errors())
		{
			return display(
				new Row(
					new Col(
						new Panel(array(
							'title'   => 'Erreurs',
							'icon'    => 'fa-warning',
							'content' => $this->error->display()
						))
					),
					'row-default'
				)
			);
		}
		
		return '';
	}

	public function profiler()
	{
		$output = '	<a href="#" data-profiler="debug"><i class="icon-chevron-'.(($this->session('profiler', 'debug')) ? 'down' : 'up').' pull-right"></i></a>
					<h2>Debug</h2>
					<div class="profiler-block">
						<table class="table table-striped">
							<tbody>';

		foreach ($this->_log as $key => $error)
		{
			list($error, $type, $file, $line) = $error;

			if ($type == self::INFO)
			{
				$type = '<span class="pull-right label label-success">Info</span>';
			}
			else if ($type == self::WARNING)
			{
				$type = '<span class="pull-right label label-warning">Warning</span>';
			}
			else if ($type == self::ERROR)
			{
				$type = '<span class="pull-right label label-danger">Error</span>';
			}

			$output .= '		<tr>
									<td style="width: 200px;"><b>'.$key.'</b>'.$type.'</td>
									<td>'.$error.'</td>
									<td style="width: 300px;">'.$file.' <span class="orange">'.$line.'</span></td>
								</tr>';
		}

		$output .= '		</tbody>
						</table>
					</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/core/profiler.php
*/