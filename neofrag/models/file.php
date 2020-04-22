<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class File extends Model2
{
	static public function __schema()
	{
		return [
			'id'   => self::field()->primary(),
			'user' => self::field()->depends('user/user')->default(NeoFrag()->user)->null(),
			'name' => self::field()->text(100),
			'path' => self::field()->text(100),
			'date' => self::field()->datetime()
		];
	}

	static public function filename($dir, $extension)
	{
		dir_create($dir = 'upload/'.($dir ?: 'unknow'));

		do
		{
			$file = unique_id().'.'.$extension;
		}
		while (check_file($filename = $dir.'/'.$file));

		return $filename;
	}

	static public function add($path, $name)
	{
		return NeoFrag()->model2('file')
						->set('name', $name)
						->set('path', $path)
						->create();
	}

	static public function uploaded_file($files, $dir = NULL, $file_id = NULL, $var = NULL)
	{
		$filename = static::filename($dir, extension(basename($var ? $files['name'][$var] : $files['name'])));

		if (move_uploaded_file($var ? $files['tmp_name'][$var] : $files['tmp_name'], $filename))
		{
			if (($file = NeoFrag()->model2('file', $file_id)) && $file->id)
			{
				@unlink($file->path);

				return $file->set('user', NeoFrag()->user)
							->set('name', $var ? $files['name'][$var] : $files['name'])
							->set('path', $filename)
							->update();
			}
			else
			{
				return static::add($filename, $var ? $files['name'][$var] : $files['name']);
			}
		}

		return FALSE;
	}

	static public function save_file($content, $file, $dir = NULL)
	{
		file_put_contents($filename = static::filename($dir, extension($file)), $content);
		return static::add($filename, $file);
	}

	public function path()
	{
		if ($this->path)
		{
			return url($this->path);
		}
	}

	public function delete()
	{
		@unlink($this->path);
		return parent::delete();
	}
}
