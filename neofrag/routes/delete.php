<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Routes;

use NF\NeoFrag\NeoFrag;

class Delete extends NeoFrag
{
	protected $_title;
	protected $_message;

	public function __construct($title, $message)
	{
		$this->_title   = $title;
		$this->_message = $message;
	}

	public function message($message)
	{
		$this->_message = $message;
		return $this;
	}

	public function __execute($model)
	{
		return $this->modal_delete('Suppression', $model::$icon)
					->body(is_a($this->_message, 'closure') ? call_user_func_array($this->_message, [$model]) : $this->_message)
					->callback(function() use ($model){
						$model->delete();
						refresh();
					});
	}
}
