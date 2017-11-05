<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

abstract class Install extends NeoFrag
{
	abstract public function up();

	public function __construct()
	{
		global $NeoFrag;
		$this->load = $NeoFrag;
	}
}
