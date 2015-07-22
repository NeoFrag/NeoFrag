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

abstract class Widget extends NeoFrag
{
	private $_widget_name;

	public $name;
	public $description;
	public $link;
	public $author;
	public $licence;
	public $version;
	public $nf_version;

	public function __construct($widget_name)
	{
		if (NeoFrag::loader()->theme)
		{
			if (in_array($theme_name = NeoFrag::loader()->theme->get_name(), array('default', 'admin')))
			{
				unset($theme_name);
			}
		}

		$this->load = new Loader(
			array(
				'assets' => array(
					'./assets',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name : '',
					'./overrides/widgets/'.$widget_name,
					'./neofrag/widgets/'.$widget_name,
					'./widgets/'.$widget_name,
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$widget_name : '',
					'./overrides/modules/'.$widget_name,
					'./neofrag/modules/'.$widget_name,
					'./modules/'.$widget_name
				),
				'controllers' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name.'/controllers' : '',
					'./overrides/widgets/'.$widget_name.'/controllers',
					'./neofrag/widgets/'.$widget_name.'/controllers',
					'./widgets/'.$widget_name.'/controllers'
				),
				'helpers' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name.'/helpers' : '',
					'./overrides/widgets/'.$widget_name.'/helpers',
					'./neofrag/widgets/'.$widget_name.'/helpers',
					'./widgets/'.$widget_name.'/helpers',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$widget_name.'/helpers' : '',
					'./overrides/modules/'.$widget_name.'/helpers',
					'./neofrag/modules/'.$widget_name.'/helpers',
					'./modules/'.$widget_name.'/helpers'
				),
				'lang' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name.'/lang' : '',
					'./overrides/widgets/'.$widget_name.'/lang',
					'./neofrag/widgets/'.$widget_name.'/lang',
					'./widgets/'.$widget_name.'/lang',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$widget_name.'/lang' : '',
					'./overrides/modules/'.$widget_name.'/lang',
					'./neofrag/modules/'.$widget_name.'/lang',
					'./modules/'.$widget_name.'/lang'
				),
				'libraries' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name.'/libraries' : '',
					'./overrides/widgets/'.$widget_name.'/libraries',
					'./neofrag/widgets/'.$widget_name.'/libraries',
					'./widgets/'.$widget_name.'/libraries',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$widget_name.'/libraries' : '',
					'./overrides/modules/'.$widget_name.'/libraries',
					'./neofrag/modules/'.$widget_name.'/libraries',
					'./modules/'.$widget_name.'/libraries'
				),
				'models' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name.'/models' : '',
					'./overrides/widgets/'.$widget_name.'/models',
					'./neofrag/widgets/'.$widget_name.'/models',
					'./widgets/'.$widget_name.'/models',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$widget_name.'/models' : '',
					'./overrides/modules/'.$widget_name.'/models',
					'./neofrag/modules/'.$widget_name.'/models',
					'./modules/'.$widget_name.'/models'
				),
				'views' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/widgets/'.$widget_name.'/views' : '',
					'./overrides/widgets/'.$widget_name.'/views',
					'./neofrag/widgets/'.$widget_name.'/views',
					'./widgets/'.$widget_name.'/views',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$widget_name.'/views' : '',
					'./overrides/modules/'.$widget_name.'/views',
					'./neofrag/modules/'.$widget_name.'/views',
					'./modules/'.$widget_name.'/views'
				)
			),
			NeoFrag::loader()
		);

		$this->_widget_name = $widget_name;

		$this->set_path();
	}

	public function get_output($type, $settings = array())
	{
		if (!is_null($controller = $this->load->controller('index')))
		{
			if (($output = $controller->method($type, array($settings))) !== FALSE)
			{
				if (!is_array($output))
				{
					$output = array($output);
				}
				
				return $output;
			}
		}

		return $this->load->widget('error')->get_output('index');
	}

	public function get_admin($type, $settings = array())
	{
		if (!is_null($controller = $this->load->controller('admin')))
		{
			if (($output = $controller->method($type, array($settings))) !== FALSE)
			{
				if (!is_array($output))
				{
					$output = array($output);
				}
				
				return $output;
			}
		}

		return '';
	}

	public function get_settings($type, $settings = array())
	{
		if (!is_null($controller = $this->load->controller('checker')))
		{
			if (($output = $controller->method($type, array($settings))) !== FALSE)
			{
				return serialize($output);
			}
		}
	}

	public function get_name()
	{
		return $this->_widget_name;
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/classes/widget.php
*/