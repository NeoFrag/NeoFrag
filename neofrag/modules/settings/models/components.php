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

class m_settings_m_components extends Model
{
	private $_addons = NULL;

	public function get_addons($type, &$list_nf, &$list)
	{
		$list = $list_nf = array();

		foreach ($this->_addons() as $object)
		{
			if ($object['type'] != $type)
			{
				continue;
			}

			$object_instance = NeoFrag::loader()->$type($object['name'], FALSE);

			if (is_null($object_instance))
			{
				continue;
			}

			if ($object_instance->is_core())
			{
				if (isset($object_instance->deactivatable) && !$object_instance->deactivatable)
				{
					continue;
				}

				$list_nf[] = $this->_data($object_instance);
			}
			else
			{
				$list[] = $this->_data($object_instance);
			}
		}
	}

	private function _addons()
	{
		if (!is_null($this->_addons))
		{
			return $this->_addons;
		}

		$addons = $this->db	->select('name', 'type', 'enable')
							->from('nf_settings_addons')
							->get();

		$this->_find('module', $addons);
		$this->_find('theme',  $addons);
		$this->_find('widget', $addons);

		return $this->_addons = $addons;
	}

	private function _find($type, &$list)
	{
		$addons = array();
		foreach ($list as $addon)
		{
			if ($addon['type'] == $type)
			{
				$addons[] = $addon['name'];
			}
		}

		foreach ($this->load->paths[$type.'s'] as $path)
		{
			if (is_dir($path))
			{
				//TODO remplacer par scandir()
				if ($dh = opendir($path))
				{
					while (($file = readdir($dh)) !== FALSE)
					{
						if (!in_array($file, array('.', '..')) && is_dir($dir = $path.'/'.$file) && file_exists($dir.'/'.strtolower($file).'.php') && !in_array($file, $addons))
						{
							$addons[] = $file;

							$list[] = array(
								'name'    => $file,
								'type'    => $type,
								'install' => FALSE
							);
						}
					}

					closedir($dh);
				}
			}
		}
	}

	private function _data($object)
	{
		return array(
			'name'        => $object->name,
			'title'       => $object->get_title(),
			'description' => $this->template->parse($object->description, array(), $object->load)
		);
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/settings/models/components.php
*/