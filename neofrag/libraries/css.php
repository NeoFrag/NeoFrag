<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Css extends Library
{
	protected $_file;

	public function __invoke($file, $media = 'screen')
	{
		$this->_file  = $file;
		$this->_media = $media;

		$this->output->data->append('css', $this);

		return $this;
	}

	public function __toString()
	{
		if (is_valid_url($this->_file))
		{
			$path = $this->_file;
		}
		else
		{
			$path = path($this->_file.'.css', 'css', $this->__caller);

			if ($v = (int)$this->config->nf_version_css)
			{
				$path .= '?v='.$v;
			}
		}

		return '<link rel="stylesheet" href="'.$path.'" type="text/css" media="'.$this->_media.'">';
	}
}
