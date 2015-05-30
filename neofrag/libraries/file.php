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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class File extends Library
{	
	public function add($files, $var, &$filename, $file_id = NULL, $dir = NULL)
	{
		if (!file_exists($dir = './upload/'.($dir ?: 'unknow')))
		{
			if (!mkdir($dir, 0777, TRUE))
			{
				return FALSE;
			}
		}
		
		do
		{
			$file = unique_id().'.'.extension(basename($files['name'][$var]));
		}
		while (file_exists($filename = $dir.'/'.$file));
		
		if (move_uploaded_file($files['tmp_name'][$var], $filename))
		{
			if ($file_id)
			{
				$this->_unlink($file_id);
				
				$this->db	->where('file_id', $file_id)
							->update('nf_files', array(
								'user_id' => $this->user() ? $this->user('user_id') : NULL,
								'path'    => $filename,
								'name'    => $files['name'][$var]
							));
				
				return $file_id;
			}
			else
			{
				return $this->db->insert('nf_files', array(
					'user_id' => $this->user() ? $this->user('user_id') : NULL,
					'path'    => $filename,
					'name'    => $files['name'][$var]
				));
			}
		}
		
		return FALSE;
	}
	
	public function delete($files)
	{
		foreach ((array)$files as $file_id)
		{
			$this->_unlink($file_id);
			
			$this->db	->where('file_id', $file_id)
						->delete('nf_files');
		}
		
		return $this;
	}
	
	private function _unlink($file_id)
	{
		if (file_exists($file = $this->db->select('path')->from('nf_files')->where('file_id', $file_id)->row()))
		{
			unlink($file);
		}
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/libraries/file.php
*/