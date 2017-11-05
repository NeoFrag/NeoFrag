<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

abstract class Widget extends Loadable
{
	static public $core = [
		'breadcrumb' => TRUE,
		'error'      => FALSE,
		'html'       => TRUE,
		'members'    => TRUE,
		'module'     => FALSE,
		'navigation' => TRUE,
		'user'       => TRUE
	];

	public function paths()
	{
		if (!empty(NeoFrag()->theme))
		{
			if (in_array($theme_name = NeoFrag()->theme->name, ['default', 'admin']))
			{
				unset($theme_name);
			}
		}

		return [
			'assets' => [
				'assets',
				'overrides/widgets/'.$this->name,
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name : '',
				'neofrag/widgets/'.$this->name,
				'widgets/'.$this->name,
				'overrides/modules/'.$this->name,
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name : '',
				'neofrag/modules/'.$this->name,
				'modules/'.$this->name
			],
			'controllers' => [
				'overrides/widgets/'.$this->name.'/controllers',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/controllers' : '',
				'neofrag/widgets/'.$this->name.'/controllers',
				'widgets/'.$this->name.'/controllers'
			],
			'helpers' => [
				'overrides/widgets/'.$this->name.'/helpers',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/helpers' : '',
				'neofrag/widgets/'.$this->name.'/helpers',
				'widgets/'.$this->name.'/helpers',
				'overrides/modules/'.$this->name.'/helpers',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/helpers' : '',
				'neofrag/modules/'.$this->name.'/helpers',
				'modules/'.$this->name.'/helpers'
			],
			'lang' => [
				'overrides/widgets/'.$this->name.'/lang',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/lang' : '',
				'neofrag/widgets/'.$this->name.'/lang',
				'widgets/'.$this->name.'/lang',
				'overrides/modules/'.$this->name.'/lang',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/lang' : '',
				'neofrag/modules/'.$this->name.'/lang',
				'modules/'.$this->name.'/lang'
			],
			'libraries' => [
				'overrides/widgets/'.$this->name.'/libraries',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/libraries' : '',
				'neofrag/widgets/'.$this->name.'/libraries',
				'widgets/'.$this->name.'/libraries',
				'overrides/modules/'.$this->name.'/libraries',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/libraries' : '',
				'neofrag/modules/'.$this->name.'/libraries',
				'modules/'.$this->name.'/libraries'
			],
			'models' => [
				'overrides/widgets/'.$this->name.'/models',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/models' : '',
				'neofrag/widgets/'.$this->name.'/models',
				'widgets/'.$this->name.'/models',
				'overrides/modules/'.$this->name.'/models',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/models' : '',
				'neofrag/modules/'.$this->name.'/models',
				'modules/'.$this->name.'/models'
			],
			'views' => [
				'overrides/widgets/'.$this->name.'/views',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/views' : '',
				'neofrag/widgets/'.$this->name.'/views',
				'widgets/'.$this->name.'/views',
				'overrides/modules/'.$this->name.'/views',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/views' : '',
				'neofrag/modules/'.$this->name.'/views',
				'modules/'.$this->name.'/views'
			]
		];
	}

	public function is_removable()
	{
		return !in_array($this->name, ['access', 'addons', 'admin', 'comments', 'error', 'live_editor', 'members', 'pages', 'search', 'settings', 'user']);
	}

	public function get_output($type, $settings = [])
	{
		if (($controller = $this->controller('index')) && $controller->has_method($type))
		{
			if (!is_array($output = $controller->method($type, [$settings])))
			{
				$output = [$output];
			}
			
			return $output;
		}

		return $this->widget('error')->get_output('index');
	}

	public function get_admin($type, $settings = [])
	{
		if (($controller = $this->controller('admin')) && $controller->has_method($type))
		{
			if (!is_array($output = $controller->method($type, [$settings])))
			{
				$output = [$output];
			}
			
			return $output;
		}

		return '';
	}

	public function get_settings($type, $settings = [])
	{
		if (($controller = $this->controller('checker')) && $controller->has_method($type))
		{
			return serialize($controller->method($type, [$settings]));
		}
	}
}
