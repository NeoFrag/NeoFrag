<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function dir_create()
{
	foreach (func_get_args() as $dir)
	{
		@mkdir($dir, 0775, TRUE);
	}
}

function dir_temp()
{
	while (file_exists($tmp = (ini_get('upload_tmp_dir') ?: sys_get_temp_dir()).'/'.unique_id()));
	return $tmp;
}

function dir_scan($dirs = '.', $callback = NULL, $dir_callback = NULL)
{
	$result = [];

	foreach ((array)$dirs as $dir)
	{
		if (!file_exists($dir))
		{
			continue;
		}

		foreach (scandir($dir) as $file)
		{
			if (in_array($file, ['.', '..']))
			{
				continue;
			}

			if (is_dir($path = $dir.'/'.$file))
			{
				$result = array_merge($result, dir_scan($path, $callback, $dir_callback));

				if (is_callable($dir_callback))
				{
					$dir_callback($path);
				}
			}
			else
			{
				$result[$path] = is_callable($callback) ? $callback($path) : $file;
			}
		}
	}

	return $result;
}

function dir_remove($directory)
{
	dir_scan($directory, 'unlink', 'dir_remove');
	rmdir($directory);
}

function dir_copy($src, $dst)
{
	dir_create($dst);

	$dir = opendir($src);

	while (($file = readdir($dir)) !== FALSE)
	{
		if (!in_array($file, ['.', '..']))
		{
			if (is_dir($src.'/'.$file))
			{
				dir_copy($src.'/'.$file, $dst.'/'.$file);
			}
			else
			{
				copy($src.'/'.$file, $dst.'/'.$file);
			}
		}
	}

	closedir($dir);
}
