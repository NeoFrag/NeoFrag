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

class m_settings_c_admin_ajax_checker extends Controller_Module
{
	public function _theme_activation()
	{
		return array($this->_check_theme());
	}
	
	public function _theme_reset()
	{
		return array($this->_check_theme());
	}
	
	public function _theme_delete()
	{
		if (!in_array($theme_name = $this->_check_theme(), array('default', $this->config->default_theme)))
		{
			return array($theme_name);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function _theme_internal($theme_name)
	{
		if (($theme = $this->load->theme($theme_name, FALSE)) && !is_null($controller = $theme->load->controller('admin_ajax')) && method_exists($controller, 'index'))
		{
			return array($controller);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function _check_theme()
	{
		$post = post();
		
		if (!empty($post['theme']) && in_array($post['theme'], array_keys($this->addons('theme'))))
		{
			return $post['theme'];
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/settings/controllers/admin_ajax_checker.php
*/