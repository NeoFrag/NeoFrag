<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class View extends Library
{
	protected $_name;
	protected $_path;
	protected $_data;

	public function __invoke($name, $data = [])
	{
		if (is_object($name))
		{
			return $name;
		}

		$this->_name = $name;
		$this->_data = $data;

		return $this;
	}

	public function __toString()
	{
		$paths = [];

		if ($path = $this->__caller->__path('views', $this->_name.'.tpl.php', $paths))
		{
			ob_start();

			$this->_path = $path;

			extract($this->_data);

			include $this->_path;

			return ob_get_clean();
		}

		trigger_error('Unfound view: '.$this->_name.' in paths ['.implode(';', $paths).']', E_USER_WARNING);

		return '';
	}

	public function path()
	{
		return $this->_path;
	}
}
