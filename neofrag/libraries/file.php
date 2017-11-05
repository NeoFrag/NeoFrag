<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class File extends Library
{
	public function filename($dir, $extension)
	{
		dir_create($dir = 'upload/'.($dir ?: 'unknow'));
		
		do
		{
			$file = unique_id().'.'.$extension;
		}
		while (check_file($filename = $dir.'/'.$file));
		
		return $filename;
	}

	public function upload($files, $dir = NULL, &$filename = NULL, $file_id = NULL, $var = NULL)
	{
		$filename = $this->filename($dir, extension(basename($var ? $files['name'][$var] : $files['name'])));

		if (move_uploaded_file($var ? $files['tmp_name'][$var] : $files['tmp_name'], $filename))
		{
			if ($file_id)
			{
				$this->_unlink($file_id);
				
				$this->db	->where('file_id', $file_id)
							->update('nf_files', [
								'user_id' => $this->user() ? $this->user('user_id') : NULL,
								'path'    => $filename,
								'name'    => $var ? $files['name'][$var] : $files['name']
							]);
				
				return $file_id;
			}
			else
			{
				return $this->add($filename, $var ? $files['name'][$var] : $files['name']);
			}
		}
		
		return FALSE;
	}
	
	public function add($path, $name)
	{
		return $this->db->insert('nf_files', [
			'user_id' => $this->user() ? $this->user('user_id') : NULL,
			'path'    => $path,
			'name'    => $name
		]);
	}
	
	public function delete($files)
	{
		foreach (array_filter((array)$files) as $file_id)
		{
			$this->_unlink($file_id);
			
			$this->db	->where('file_id', $file_id)
						->delete('nf_files');
		}
		
		return $this;
	}
	
	private function _unlink($file_id)
	{
		if (check_file($file = $this->db->select('path')->from('nf_files')->where('file_id', $file_id)->row()))
		{
			unlink($file);
		}
	}
}
