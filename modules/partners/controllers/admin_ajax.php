<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */
 
class m_partners_c_admin_ajax extends Controller
{
	public function sort($partner_id, $position)
	{
		$partners = [];

		foreach ($this->db->select('partner_id')->from('nf_partners')->where('partner_id !=', $partner_id)->order_by('order', 'partner_id')->get() as $partner)
		{
			$partners[] = $partner;
		}

		foreach (array_merge(array_slice($partners, 0, $position, TRUE), [$partner_id], array_slice($partners, $position, NULL, TRUE)) as $order => $partner_id)
		{
			$this->db	->where('partner_id', $partner_id)
						->update('nf_partners', [
							'order' => $order
						]);
		}
	}
}
