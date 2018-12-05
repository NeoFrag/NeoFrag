<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Tools\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Api extends Controller_Module
{
	public function scss($action = 'reload')
	{
		$list_scss_files = function(){
			$files = [];

			dir_scan('.', function($file) use (&$files){
				if (preg_match('#\.scss$#', $file, $match))
				{
					$files[] = $file;
				}
			});

			return $files;
		};

		spl_autoload_register(function($name){
			if (preg_match('_^Leafo\\\ScssPhp_', $name))
			{
				require_once 'lib/'.str_replace('\\', '/', preg_replace('_^Leafo\\\ScssPhp_', 'scssphp', $name)).'.php';
			}
		});

		$compile = function($files){
			$results = [];

			foreach ($files as $file)
			{
				if (preg_match('#/sass/((?!_)[a-z0-9_.-]+)\.scss$#', $file, $match))
				{
					$path = preg_replace('#/sass/[^/]*?\.scss#', '', $file);
					$css  = $path.'/'.$match[1].'.css';

					$scss = new \Leafo\ScssPhp\Compiler();
					$scss->setFormatter('Leafo\ScssPhp\Formatter\Crunched');
					$scss->setSourceMap(\Leafo\ScssPhp\Compiler::SOURCE_MAP_FILE);
					$scss->preprocessingFunction(function($file){
						ob_start();
						include $file;
						return ob_get_clean();
					});
					$scss->setImportPaths($path.'/sass');
					$scss->setSourceMapOptions([
						'sourceMapWriteTo' => $css.'.map',
						'sourceMapURL'     => $match[1].'.css.map',
						'sourceRoot'       => '/'
					]);

					try
					{
						$md5 = md5_file($css);
						file_put_contents($css, @$scss->compile_file($file));

						if ($md5 != md5_file($css))
						{
							$results[] = $css;
						}
					}
					catch (\Exception $e)
					{
						echo "Error $file\n\t--> ".$e->getMessage()."\n";
					}
				}
			}

			return $results;
		};

		if ($action == 'reload')
		{
			foreach ($compile($list_scss_files()) as $file)
			{
				echo $file."\n";
			}

			$this->config('nf_version_css', time());

			return 'OK';
		}
		else if ($action == 'watch')
		{
			echo "Watching...\n";

			$files = [];
			$first = TRUE;

			while (TRUE)
			{
				$need_update = [];

				foreach ($scan = $list_scss_files() as $file)
				{
					if (!array_key_exists($file, $files))
					{
						$files[$file] = filemtime($file);
						$need_update['Added'][] = $file;
					}
					else if ($files[$file] != ($time = filemtime($file)))
					{
						$files[$file] = $time;
						$need_update['Updated'][] = $file;
					}
				}

				foreach (array_diff(array_keys($files), $scan) as $file)
				{
					$need_update['Removed'][] = $file;
					unset($files[$file]);
				}

				if ($need_update)
				{
					foreach ($updated = $compile($scan) as $file)
					{
						$need_update['-->'][] = $file;
					}

					if (!$first && isset($need_update['-->']))
					{
						foreach ($need_update as $type => $f)
						{
							echo "$type\n".implode("\n", array_map(function($a){
								return "\t".$a;
							}, $f))."\n";
						}
					}

					$first = FALSE;
				}

				usleep(200000);
			}
		}
	}
}
