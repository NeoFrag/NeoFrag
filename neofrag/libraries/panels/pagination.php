<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Panel_Pagination extends Panel
{
	public function __toString()
	{
		if ($pagination = NeoFrag()->module->pagination->get_pagination())
		{
			return '<div class="pull-right">'.$pagination.'</div>';
		}

		return '';
	}
}
