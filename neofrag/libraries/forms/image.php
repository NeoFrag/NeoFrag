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

	public function __invoke($name, $upload_dir = '')
	{
		$this->_precheck = function($file){
			list($width, $height) = getimagesize($file);

			if ($this->_width != $width || $this->_height != $height)
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
			return '<div class="text-center">
						<img class="img-thumbnail" src="'.$this->_value->path().'" alt="" />
						<p class="m-5">'.$this->lang('Dimensions %dpx par %dpx (%s max.)', $this->_width, $this->_height, human_size(file_upload_max_size())).'</p>
					</div>';
		};

		return parent::__invoke($name, $upload_dir);
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
