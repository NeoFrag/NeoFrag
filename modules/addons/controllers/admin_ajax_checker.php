<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_addons_c_admin_ajax_checker extends Controller_Module
{
	public function active()
	{
		$post = post();

		if (!empty($post['type']))
		{
			if (in_array($post['type'], ['module', 'widget']) && !empty($post['name']) && ($object = $this->{$post['type']}($post['name'], TRUE)) && $object->is_deactivatable())
			{
				return [$post['type'], $object];
			}
			else if ($post['type'] == 'authenticator' && !empty($post['name']) && ($authenticator = $this->db->from('nf_settings_authenticators')->where('name', $post['name'])->row()))
			{
				return [$post['type'], $this->authenticator($authenticator['name'], $authenticator['is_enabled'], unserialize($authenticator['settings']))];
			}
		}
	}

	public function _theme_activation()
	{
		return [$this->_check_theme()];
	}

	public function _theme_reset()
	{
		return [$this->_check_theme()];
	}

	public function _theme_settings($theme_name)
	{
		if (($theme = $this->theme($theme_name)) && ($controller = $theme->controller('admin_ajax')) && $controller->has_method('index'))
		{
			return [$controller];
		}
	}

	private function _check_theme()
	{
		$post = post();

		if (!empty($post['theme']) && $theme = $this->theme($post['theme']))
		{
			return $theme;
		}
	}

	public function _language_sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_settings_languages')->where('code', $check['id'])->row())
		{
			return $check;
		}
	}

	public function _authenticator_sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_settings_authenticators')->where('name', $check['id'])->row())
		{
			return $check;
		}
	}

	public function _authenticator_admin()
	{
		$this->extension('json');

		if (($check = post_check('name')) && ($auth = $this->db->from('nf_settings_authenticators')->where('name', $check['name'])->row()))
		{
			return [$this->authenticator($auth['name'], $auth['is_enabled'], unserialize($auth['settings']))];
		}
	}

	public function _authenticator_update()
	{
		if (($check = post_check('name', 'settings')) && ($auth = $this->db->from('nf_settings_authenticators')->where('name', $check['name'])->row()))
		{
			return [$this->authenticator($auth['name'], $auth['is_enabled'], unserialize($auth['settings'])), $check['settings']];
		}
	}
}
