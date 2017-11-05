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
		$paths = [];

		if ($path = $this->__caller->__path('views', $this->_name.'.tpl.php', $paths))
		{
			return $this->content(file_get_contents($path), $this->_data);
		}

		trigger_error('Unfound view: '.$this->_name.' in paths ['.implode(';', $paths).']', E_USER_WARNING);

		return '';
	}
}
