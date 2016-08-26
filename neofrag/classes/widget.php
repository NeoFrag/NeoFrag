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
		if (!empty(NeoFrag::loader()->theme))
		{
			if (in_array($theme_name = NeoFrag::loader()->theme->name, ['default', 'admin']))
			{
				unset($theme_name);
			}
		}

		return [
			'assets' => [
				'assets',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name : '',
				'overrides/widgets/'.$this->name,
				'neofrag/widgets/'.$this->name,
				'widgets/'.$this->name,
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name : '',
				'overrides/modules/'.$this->name,
				'neofrag/modules/'.$this->name,
				'modules/'.$this->name
			],
			'controllers' => [
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/controllers' : '',
				'overrides/widgets/'.$this->name.'/controllers',
				'neofrag/widgets/'.$this->name.'/controllers',
				'widgets/'.$this->name.'/controllers'
			],
			'helpers' => [
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/helpers' : '',
				'overrides/widgets/'.$this->name.'/helpers',
				'neofrag/widgets/'.$this->name.'/helpers',
				'widgets/'.$this->name.'/helpers',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/helpers' : '',
				'overrides/modules/'.$this->name.'/helpers',
				'neofrag/modules/'.$this->name.'/helpers',
				'modules/'.$this->name.'/helpers'
			],
			'lang' => [
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/lang' : '',
				'overrides/widgets/'.$this->name.'/lang',
				'neofrag/widgets/'.$this->name.'/lang',
				'widgets/'.$this->name.'/lang',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/lang' : '',
				'overrides/modules/'.$this->name.'/lang',
				'neofrag/modules/'.$this->name.'/lang',
				'modules/'.$this->name.'/lang'
			],
			'libraries' => [
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/libraries' : '',
				'overrides/widgets/'.$this->name.'/libraries',
				'neofrag/widgets/'.$this->name.'/libraries',
				'widgets/'.$this->name.'/libraries',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/libraries' : '',
				'overrides/modules/'.$this->name.'/libraries',
				'neofrag/modules/'.$this->name.'/libraries',
				'modules/'.$this->name.'/libraries'
			],
			'models' => [
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/models' : '',
				'overrides/widgets/'.$this->name.'/models',
				'neofrag/widgets/'.$this->name.'/models',
				'widgets/'.$this->name.'/models',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/models' : '',
				'overrides/modules/'.$this->name.'/models',
				'neofrag/modules/'.$this->name.'/models',
				'modules/'.$this->name.'/models'
			],
			'views' => [
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/widgets/'.$this->name.'/views' : '',
				'overrides/widgets/'.$this->name.'/views',
				'neofrag/widgets/'.$this->name.'/views',
				'widgets/'.$this->name.'/views',
				!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/views' : '',
				'overrides/modules/'.$this->name.'/views',
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
		if (($controller = $this->load->controller('index')) && ($output = $controller->method($type, [$settings])) !== FALSE)
		{
			if (!is_array($output))
			{
				$output = [$output];
			}
			
			return $output;
		}

		return $this->load->widget('error')->get_output('index');
	}

	public function get_admin($type, $settings = [])
	{
		if (($controller = $this->load->controller('admin')) && ($output = $controller->method($type, [$settings])) !== FALSE)
		{
			if (!is_array($output))
			{
				$output = [$output];
			}
			
			return $output;
		}

		return '';
	}

	public function get_settings($type, $settings = [])
	{
		if (($controller = $this->load->controller('checker')) && ($output = $controller->method($type, [$settings])) !== FALSE)
		{
			return serialize($output);
		}
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/widget.php
*/