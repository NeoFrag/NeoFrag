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

class m_partners_m_partners extends Model
{
	public function get_partners()
	{
		return $this->db->select('p.partner_id', 'p.name', 'p.logo_light', 'p.logo_dark', 'p.website', 'p.facebook', 'p.twitter', 'p.count', 'p.code', 'p.order', 'pl.title', 'pl.description')
						->from('nf_partners p')
						->join('nf_partners_lang pl', 'p.partner_id  = pl.partner_id')
						->where('pl.lang', $this->config->lang)
						->order_by('p.order', 'p.partner_id')
						->get();
	}

	public function check_partner($partner_id, $name)
	{
		return $this->db->select('p.partner_id', 'p.name', 'p.logo_light', 'p.logo_dark', 'p.website', 'p.facebook', 'p.twitter', 'p.count', 'p.code', 'pl.title', 'pl.description')
						->from('nf_partners p')
						->join('nf_partners_lang pl', 'p.partner_id  = pl.partner_id')
						->where('p.partner_id', $partner_id)
						->where('p.name', $name)
						->where('pl.lang', $this->config->lang)
						->row();
	}

	public function add_partner($title, $logo_light, $logo_dark, $description, $website, $facebook, $twitter, $code)
	{
		$partner_id = $this->db->insert('nf_partners', [
			'name'       => url_title($title),
			'logo_light' => $logo_light,
			'logo_dark'  => $logo_dark,
			'website'    => $website,
			'facebook'   => $facebook,
			'twitter'    => $twitter,
			'code'       => $code
		]);

		$this->db->insert('nf_partners_lang', [
			'partner_id'  => $partner_id,
			'lang'        => $this->config->lang,
			'title'       => $title,
			'description' => $description
		]);
	}

	public function edit_partner($partner_id, $title, $logo_light, $logo_dark, $description, $website, $facebook, $twitter, $code)
	{
		$this->db	->where('partner_id', $partner_id)
					->update('nf_partners', [
						'name'       => url_title($title),
						'logo_light' => $logo_light,
						'logo_dark'  => $logo_dark,
						'website'    => $website,
						'facebook'   => $facebook,
						'twitter'    => $twitter,
						'code'       => $code
					]);

		$this->db	->where('partner_id', $partner_id)
					->where('lang', $this->config->lang)
					->update('nf_partners_lang', [
						'title'       => $title,
						'description' => $description
					]);
	}

	public function delete_partner($partner_id)
	{
		$this->file->delete($this->db->select('logo_light', 'logo_dark')->from('nf_partners')->where('partner_id', $partner_id)->row());

		$this->db	->where('partner_id', $partner_id)
					->delete('nf_partners');
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/partners/models/partners.php
*/