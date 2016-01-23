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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_addons_c_admin_ajax_checker extends Controller_Module
{
	public function active()
	{
		$post = post();

		if (!empty($post['type']) && in_array($post['type'], array('module', 'widget')) && !empty($post['name']) && ($object = $this->load->{$post['type']}($post['name'], TRUE)) && $object->is_deactivatable())
		{
			return array($post['type'], $object->name);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _theme_activation()
	{
		return array($this->_check_theme());
	}

	public function _theme_reset()
	{
		return array($this->_check_theme());
	}

	public function _theme_settings($theme_name)
	{
		if (($theme = $this->load->theme($theme_name)) && ($controller = $theme->load->controller('admin_ajax')) !== NULL && method_exists($controller, 'index'))
		{
			return array($controller);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}

	private function _check_theme()
	{
		$post = post();
		
		if (!empty($post['theme']) && $this->load->theme($post['theme']))
		{
			return $post['theme'];
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function _language_sort()
	{
		if (($check = $this->_check('id', 'position')) && $this->db->select('1')->from('nf_settings_languages')->where('code', $check['id'])->row())
		{
			return $check;
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	private function _check()
	{
		if (!array_diff(func_get_args(), array_keys($args = array_intersect_key(post(), array_flip(func_get_args())))))
		{
			return $args;
		}
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/addons/controllers/admin_ajax_checker.php
*/