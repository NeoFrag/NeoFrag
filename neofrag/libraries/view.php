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

class View extends Library
{
	protected $_name;
	protected $_data;

	public function __invoke($name, $data = [])
	{
		$this->_name = $name;
		$this->_data = $data;

		return $this->reset();
	}

	public function content($content, $data = [])
	{
		if (in_string('<?php', $content))
		{
			$content = eval('ob_start(); ?>'.$content.'<?php return ob_get_clean();');
		}

		return $content;
	}

	public function __toString()
	{
		foreach ($paths = $this->load->paths('views') as $dir)
		{
			if (check_file($path = $dir.'/'.$this->_name.'.tpl.php'))
			{
				$data = array_merge($this->_data, $this->load->data);

				if ($this->debug->is_enabled())
				{
					$this->load->views[] = [$path, $this->_name.'.tpl.php', $data];
				}

				return $this->content(file_get_contents($path), $data);
			}
		}

		trigger_error('Unfound view: '.$this->_name.' in paths ['.implode(', ', array_filter($paths)).']', E_USER_WARNING);

		return '';
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/view.php
*/