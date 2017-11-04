<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Js extends Library
{
	protected $_file;

	public function __invoke($file)
	{
		$this->_file = $file;

		$this->output->data->append('js', $this);

		return $this;
	}

	public function __toString()
	{
		static $js = [];

		if (array_key_exists($path = $this->path(), $js))
		{
			return '';
		}

		$js[$path] = TRUE;

		return '<script type="text/javascript" src="'.$path.'"></script>';
	}

	public function path()
	{
		if (is_valid_url($this->_file))
		{
			$path = $this->_file;
		}
		else
		{
			$path = path($this->_file.'.js', 'js', $this->__caller);

			if ($v = (int)$this->config->nf_version_css)
			{
				$path .= '?v='.$v;
			}
		}

		return $path;
	}
}
