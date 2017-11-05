<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Addons extends Core
{
	private $_addons = [];
	
	public function __construct()
	{
		parent::__construct();

		foreach ($this->db->from('nf_settings_addons')->get() as $addon)
		{
			$this->_addons[$addon['type']][$addon['name']] = (bool)$addon['is_enabled'];
		}
		
		foreach (['module', 'widget'] as $type)
		{
			foreach ($type::$core as $name => $deactivatable)
			{
				if (!isset($this->_addons[$type][$name]) || !$deactivatable)
				{
					$this->_addons[$type][$name] = TRUE;
				}
			}
		}

		foreach ($this->db->select('code', 'name', 'flag')->from('nf_settings_languages')->order_by('order')->get() as $language)
		{
			$this->_addons['language'][array_shift($language)] = $language;
		}
	}
	
	public function is_enabled($name, $type)
	{
		return !empty($this->_addons[$type][$name]);
	}
	
	private function _get_addons($get_all, $type)
	{
		static $list = [];
		
		if (!isset($list[$type][(int)$get_all]))
		{
			foreach ($this->_addons[$type] as $name => $is_enabled)
			{
				if (($object = NeoFrag()->$type($name, $get_all)) && ($is_enabled || $get_all))
				{
					$list[$type][(int)$get_all][$name] = $object;
				}
			}
		}

		return $list[$type][(int)$get_all];
	}

	public function get_modules($get_all = FALSE)
	{
		return $this->_get_addons($get_all, 'module');
	}
	
	public function get_widgets($get_all = FALSE)
	{
		return $this->_get_addons($get_all, 'widget');
	}

	public function get_themes()
	{
		static $list;
		
		if ($list === NULL)
		{
			$list = [];

			foreach (array_keys($this->_addons['theme']) as $name)
			{
				if ($theme = NeoFrag()->theme($name))
				{
					$list[$name] = $theme;
				}
			}
			
			array_natsort($list, function($a){
				return $a->get_title();
			});
		}

		return $list;
	}

	public function get_languages()
	{
		return $this->_addons['language'];
	}

	public function get_authenticators($get_all = FALSE)
	{
		$authenticators = [];

		foreach ($this->db->from('nf_settings_authenticators')->order_by('order')->get() as $auth)
		{
			if (($auth['is_enabled'] || $get_all) && ($authenticator = $this->authenticator($auth['name'], $auth['is_enabled'], unserialize($auth['settings']))))
			{
				$authenticators[] = $authenticator;
			}
		}

		return $authenticators;
	}
}
