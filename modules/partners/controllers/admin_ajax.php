<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/
 
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

/*
NeoFrag Alpha 0.1.4
./modules/partners/controllers/admin_ajax.php
*/