<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	private $_notifications = [];

	public function index($refresh)
	{
		if ($refresh || $this->module->need_checking())
		{
			$this->config('nf_monitoring_last_check', time());

			if (version_compare(PHP_VERSION, 7, '<'))
			{
				$this->_notify('Il est recommandé d\'utiliser PHP 7', 'info');
			}

			if ($this->db->get_info('driver') != 'mysqli')
			{
				$this->_notify('Il est recommandé d\'utiliser MySQLi', 'info');
			}

			dir_create('cache/monitoring');

			foreach (['version', 'checksum'] as $file)
			{
				if ($$file = $this	->network('https://neofr.ag/'.$file.'.json')
									->type('text')
									->get())
				{
					file_put_contents('cache/monitoring/'.$file.'.json', $$file);
					$$file = (array)json_decode($$file);
				}
			}

			if ($checksum)
			{
				foreach (array_merge(dir_scan($this->model()->folders, 'md5_file'), ['index.php' => md5_file('index.php')]) as $file => $md5)
				{
					if (!isset($checksum[$file]))
					{
						$checksum[$file] = '';
					}

					$checksum[$file] .= '|'.$md5;
				}

				uksort($checksum, function($a, $b){
					$fct = function(&$a, &$b){
						$a = explode('/', $a);
						$b = array_pop($a);
					};

					$fct($a, $aa);
					$fct($b, $bb);

					$fct2 = function(&$a, &$b){
						foreach (range(0, max(count($a), count($b))) as $i)
						{
							if (isset($a[$i], $b[$i]) && $a[$i] == $b[$i])
							{
								unset($a[$i], $b[$i]);
							}
							else
							{
								break;
							}
						}
					};

					$fct2($a, $b);

					if (empty($a) && !empty($b))
					{
						return 1;
					}
					else if (!empty($a) && empty($b))
					{
						return -1;
					}
					else if (($cmp = strnatcmp(implode('/', $a), implode('/', $b))) != 0)
					{
						return $cmp;
					}
					else
					{
						do
						{
							$fct3 = function(&$a, &$b){
								$b = explode('.', $a);
								$a = str_split(array_shift($b));
								$b = implode('.', $b);
							};

							$fct3($aa, $aaa);
							$fct3($bb, $bbb);
							$fct2($aa, $bb);

							if (($cmp = strnatcmp(implode($aa), implode($bb))) != 0)
							{
								return $cmp;
							}

							$aa = $aaa;
							$bb = $bbb;
						}
						while ($aa || $bb);
					}
				});

				$tree = [];

				foreach ($checksum as $file => $md5)
				{
					$dirs = explode('/', $file);
					$file = array_pop($dirs);

					$node = &$tree;

					foreach ($dirs as $dir)
					{
						if (!isset($node[$dir]))
						{
							$node[$dir] = [];
						}

						$node = &$node[$dir];
					}

					if (!isset($node[$file]))
					{
						$node[$file] = '';
					}

					$node[$file] .= $md5;
					unset($node);
				}

				$treeview = function($tree, $dir = '') use (&$treeview){
					$output = [];

					foreach ($tree as $name => $node)
					{
						if (is_array($node))
						{
							$tags = [];

							if (file_exists($dir.$name) && !is_writable($dir.$name))
							{
								$tags[] = 'Protégé en écriture';
								$this->_notify('Le dossier <code>'.$dir.$name.'</code> est protégé en écriture', 'warning');
							}

							$output[] = [
								'text'  => $name,
								'tags'  => $tags,
								'nodes' => $treeview($node, $dir.$name.'/')
							];
						}
						else
						{
							$node = explode('|', $node);

							if (!isset($node[1]))
							{
								$node[] = '';
							}

							list($nf_md5, $md5) = $node;

							$tags = [];

							if (!preg_match('#^(?:backups|cache|config|overrides|upload)/#', $dir))
							{
								if ($nf_md5 === '')
								{
									if (!preg_match('#^(?:modules|themes|widgets)/#', $dir))
									{
										$tags[] = 'Inconnu';
										$this->_notify('Le fichier <code>'.$dir.$name.'</code> ne devrait pas se trouver là', 'error');
									}
								}
								else if ($md5 === '')
								{
									$tags[] = 'Manquant';
									$this->_notify('Le fichier <code>'.$dir.$name.'</code> est manquant', 'error');
								}
								else if ($nf_md5 != $md5)
								{
									$tags[] = 'Corrompu';
									$this->_notify('Le fichier <code>'.$dir.$name.'</code> est corrompu', 'warning');
								}
							}

							if ($md5 !== '' && !is_writable($dir.$name))
							{
								$tags[] = 'Protégé en écriture';
								$this->_notify('Le fichier <code>'.$dir.$name.'</code> est protégé en écriture', 'warning');
							}

							$output[] = [
								'text' => $name,
								'tags' => $tags
							];
						}
					}

					return $output;
				};
			}
			else if (extension_loaded('curl'))
			{
				if (!($cainfo = ini_get('curl.cainfo')) || !file_exists($cainfo))
				{
					$this->_notify('Problème de téléchargement, veuillez configurer <a href="http://php.net/manual/fr/curl.configuration.php#ini.curl.cainfo" target="_blank">curl.cainfo</a>', 'error');
				}
				else
				{
					$this->_notify('Problème de téléchargement, erreur inconnue', 'error');
				}
			}

			$server = [];

			foreach ($this->model()->check_server() as $check)
			{
				foreach ($check['check'] as $name => $check)
				{
					$title = NULL;
					$result = $check['check']($this->_notifications, $title);
					$server[$name] = $title === NULL ? $result : [$result, $title];
				}
			}

			$result = [
				'storage' => [
					'total'    => disk_total_space(NEOFRAG_CMS) ?: 0,
					'free'     => disk_free_space(NEOFRAG_CMS) ?: 0,
					'files'    => array_sum(dir_scan($this->model()->folders, 'filesize')) + filesize('index.php'),
					'database' => $this->db->get_size()
				],
				'server' => $server
			];

			$result['files']         = $checksum ? $treeview($tree) : [];
			$result['notifications'] = $this->_notifications;

			file_put_contents('cache/monitoring/monitoring.json', json_encode($result));
		}
		else
		{
			$result = json_decode(file_get_contents('cache/monitoring/monitoring.json'));
		}

		return $result;
	}

	public function backup()
	{
		$this->_stream(function(){
			$this->_backup();
		});
	}

	public function update()
	{
		$this->_stream(function(){
			$this->_backup();

			dir_create('cache/monitoring');

			$this	->network('https://neofrag.download')
					->stream($file = 'cache/monitoring/neofrag.zip', function($size, $total){
						$this->_flush(2, $size / $total * 100);
					});

			$scan_zip = function($callback) use ($file){
				if ($zip = zip_open($file))
				{
					while ($zip_entry = zip_read($zip))
					{
						if (preg_match($a = '#^('.implode('|', array_merge(array_map(function($a){ return $a.'/'; }, array_diff($this->model()->folders, ['config'])), ['index.php', '.htaccess'])).')#', $entry_name = zip_entry_name($zip_entry)))
						{
							if (substr($entry_name, -1) == '/')
							{
								dir_create($entry_name);
							}
							else if (zip_entry_open($zip, $zip_entry, 'r'))
							{
								$callback($zip_entry, $entry_name);
							}
						}

						zip_entry_close($zip_entry);
					}
				}

				zip_close($zip);
			};

			$files = [];

			$scan_zip(function($zip_entry, $entry_name) use (&$files){
				$files[] = $entry_name;
			});

			if ($total = count($files))
			{
				$scan_zip(function($zip_entry, $entry_name) use ($total){
					static $i = 0;
					$this->_flush(3, ++$i / $total * 100);
					file_put_contents($entry_name, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
				});

				unlink($file);

				foreach (array_diff(array_keys(dir_scan('neofrag')), array_filter($files, function($a){
					return preg_match('_^neofrag/_', $a);
				})) as $file)
				{
					unlink($file);
				}

				$patchs = array_map('version_format', dir_scan('neofrag/install'));

				if (!$this->config->nf_version)
				{
					$this->config('nf_version', version_format(NEOFRAG_VERSION));
				}

				$total = count($patchs = array_filter($patchs, function($a){
					return version_compare($a, $this->config->nf_version, '>');
				}));

				uasort($patchs, 'version_compare');

				$i = 0;

				foreach ($patchs as $path => $version)
				{
					$class = 'i_'.str_replace('.', '_', $version);
					include_once $path;
					$install = new $class;
					$install->up();
					$this->_flush(4, ++$i / $total * 100);
				}

				$this	->config('nf_version', end($patchs))
						->config('nf_monitoring_last_check', 0);
			}
		});
	}

	private function _flush($step, $value)
	{
		$value = ceil($value);

		static $i;
		static $n;

		if ($i === NULL || $n != $step || $i != $value)
		{
			if ($i !== NULL)
			{
				echo ';';
			}

			echo json_encode([$n = $step, $i = $value]).PHP_EOL;

			if (ob_get_level())
			{
				ob_end_flush();
			}

			flush();
		}
	}

	private function _stream($callback)
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		set_time_limit(0);

		$callback();

		exit;
	}

	private function _backup()
	{
		dir_create('backups');

		while (file_exists(($file = 'backups/'.date('YmdHis')).'.zip') || file_exists($dump = $file.'.sql'))
		{
			sleep(1);
		}

		$this->mysqldump->dump(fopen($dump, 'w+b'), function($value){
			$this->_flush(0, $value);
		});

		$zip = new \ZipArchive;
		$zip->open($file.'.zip', \ZipArchive::CREATE);

		$zip->addFile($dump, 'DATABASE.sql');

		$files = array_merge(array_keys(dir_scan(array_diff($this->model()->folders, ['backups']))), ['index.php', '.htaccess']);

		$total = count($files);
		$i     = 0;

		foreach ($files as $file)
		{
			$zip->addFile($file);

			$this->_flush(1, ++$i / $total * 100);
		}

		$zip->close();

		unlink($dump);
	}

	private function _notify($message, $type = 'danger')
	{
		if (!in_array($type, array_keys(get_colors())))
		{
			$type = 'danger';
		}

		$this->_notifications[] = [$message, $type];
	}
}
