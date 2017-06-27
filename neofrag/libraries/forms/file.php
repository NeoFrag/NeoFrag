<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class File extends Labelable
{
	protected $_type = [];
	protected $_upload_dir;
	protected $_uploaded;

	public function __invoke($name, $upload_dir = '')
	{
		$this->_upload_dir = $upload_dir;

		$this->_template[] = function(&$input){
			$input = parent	::html('input', TRUE)
							->attr('type', 'file')
							->attr_if($this->_disabled, 'disabled');
		};

		return parent::__invoke($name);
	}

	public function type($type)
	{
		$this->_type = func_get_args();
		return $this;
	}

	public function uploaded($uploaded)
	{
		$this->_uploaded = $uploaded;
		return $this;
	}
}
