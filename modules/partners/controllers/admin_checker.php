<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_partners_c_admin_checker extends Controller_Module
{
	public function _edit($partner_id, $name)
	{
		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return $partner;
		}
	}

	public function delete($partner_id, $name)
	{
		$this->ajax();

		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return [$partner['partner_id'], $partner['title']];
		}
	}
}
