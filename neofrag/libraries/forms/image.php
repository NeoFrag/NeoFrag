<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Image extends File
{
	protected $_mimes = [
		'image/png',
		'image/jpeg',
		'image/gif'
	];
	protected $_width;
	protected $_height;
	protected $_default;

	public function __invoke($name, $upload_dir = '')
	{
		$this->_precheck = function($file){
			list($width, $height) = getimagesize($file);

			if (($this->_width && $this->_width != $width) || ($this->_height && $this->_height != $height))
			{
				if ($this->_width == $this->_height)
				{
					$this->_errors[] = $this->lang('L\'image doit être un carré de %dpx de côté', $this->_width);
				}
				else
				{
					$this->_errors[] = $this->lang('L\'image doit faire %dpx par %dpx', $this->_width, $this->_height);
				}
			}
		};

		$this->_thumbnail = function(){
			return $this->html()
						->attr('class', 'text-center')
						->append_if($path = ($this->_value ? $this->_value->path() : $this->_default), '<img class="img-thumbnail" src="'.$path.'" alt="" />')
						->append_if($this->_width || $this->_height,                  '<p class="m-4">'.$this->lang('Dimensions %dpx par %dpx <i>(%s max.)</i>', $this->_width, $this->_height, human_size(file_upload_max_size())).'</p>');
		};

		return parent::__invoke($name, $upload_dir);
	}

	public function __call($name, $args)
	{
		//TODO 5.6 compatibility
		if ($name == 'default')
		{
			$this->_default = $args[0];
			return $this;
		}

		return parent::__call($name, $args);
	}

	public function square($size)
	{
		$this->_width = $this->_height = $size;
		return $this;
	}

	public function rectangle($width, $height)
	{
		$this->_width  = $width;
		$this->_height = $height;
		return $this;
	}
}
