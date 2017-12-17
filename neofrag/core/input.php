<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Input extends Core
{
	public $get;
	public $post;

	public function __construct()
	{
		$this->get  = $this->array($_GET);
		$this->post = $this->array($_POST);
	}
}
