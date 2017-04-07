<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class File
{
	public function init($field)
	{
		$field->depends('file', '');
	}
}
