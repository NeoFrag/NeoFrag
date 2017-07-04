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
	protected $_data;

	public function __invoke($name, $data = [])
	{
		$this->_name = $name;
		$this->_data = $data;

		return $this;
	}

	public function content($__content, $data = [])
	{
		if (in_string('<?php', $__content))
		{
			foreach ($data as $var => $value)
			{
				$$var = $value;
			}

			$__content = eval('ob_start(); ?>'.$__content.'<?php return ob_get_clean();');
		}

		return $__content;
	}

	public function __toString()
	{
		$paths = [];

		if ($path = $this->__caller->__path('views', $this->_name.'.tpl.php', $paths))
		{
			return $this->content(file_get_contents($path), $this->_data);
		}

		trigger_error('Unfound view: '.$this->_name.' in paths ['.implode(';', $paths).']', E_USER_WARNING);

		return '';
	}
}
