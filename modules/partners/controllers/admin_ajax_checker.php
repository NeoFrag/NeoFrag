<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */
 
class m_partners_c_admin_ajax_checker extends Controller
{
	public function sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_partners')->where('partner_id', $check['id'])->row())
		{
			return $check;
		}
	}
}
