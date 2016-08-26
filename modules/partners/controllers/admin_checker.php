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

class m_partners_c_admin_checker extends Controller_Module
{
	public function _edit($partner_id, $name)
	{
		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return $partner;
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function delete($partner_id, $name)
	{
		$this->ajax();

		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return [$partner['partner_id'], $partner['title']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/partners/controllers/admin_checker.php
*/