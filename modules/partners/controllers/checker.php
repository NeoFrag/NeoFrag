<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_partners_c_checker extends Controller_Module
{
	public function _partner($partner_id, $name)
	{
		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			$this->db	->where('partner_id', $partner_id)
						->update('nf_partners', [
							'count' => $partner['count'] + 1
						]);

			header('Location: '.$partner['website']);
			exit;
		}
	}
}
