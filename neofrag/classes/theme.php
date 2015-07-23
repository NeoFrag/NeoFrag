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

abstract class Theme extends NeoFrag
{
	private $_theme_name;

	public $name;
	public $description;
	public $link;
	public $author;
	public $licence;
	public $version;
	public $nf_version;
	public $styles;
	
	abstract public function styles_row();
	abstract public function styles_widget();

	public function __construct($theme_name)
	{
		$this->load = new Loader(
			array(
				'assets' => array(
					'./overrides/themes/'.$theme_name,
					'./neofrag/themes/'.$theme_name,
					'./themes/'.$theme_name
				),
				'controllers' => array(
					'./overrides/themes/'.$theme_name.'/controllers',
					'./neofrag/themes/'.$theme_name.'/controllers',
					'./themes/'.$theme_name.'/controllers'
				),
				'forms' => array(
					'./overrides/themes/'.$theme_name.'/forms',
					'./neofrag/themes/'.$theme_name.'/forms',
					'./themes/'.$theme_name.'/forms'
				),
				'helpers' => array(
					'./overrides/themes/'.$theme_name.'/helpers',
					'./neofrag/themes/'.$theme_name.'/helpers',
					'./themes/'.$theme_name.'/helpers'
				),
				'lang' => array(
					'./overrides/themes/'.$theme_name.'/lang',
					'./neofrag/themes/'.$theme_name.'/lang',
					'./themes/'.$theme_name.'/lang'
				),
				'libraries' => array(
					'./overrides/themes/'.$theme_name.'/libraries',
					'./neofrag/themes/'.$theme_name.'/libraries',
					'./themes/'.$theme_name.'/libraries'
				),
				'models' => array(
					'./overrides/themes/'.$theme_name.'/models',
					'./neofrag/themes/'.$theme_name.'/models',
					'./themes/'.$theme_name.'/models'
				),
				'views' => array(
					'./overrides/themes/'.$theme_name.'/views',
					'./neofrag/themes/'.$theme_name.'/views',
					'./themes/'.$theme_name.'/overrides/views',
					'./themes/'.$theme_name.'/views'
				)
			),
			NeoFrag::loader()
		);

		$this->_theme_name = $theme_name;

		$this->set_path();
	}

	public function get_name()
	{
		return $this->_theme_name;
	}
	
	public function install($dispositions = array())
	{
		foreach ($dispositions as $page => $dispositions)
		{
			foreach ($dispositions as $zone => $disposition)
			{
				$this->db->insert('nf_dispositions', array(
					'theme'       => $this->_theme_name,
					'page'        => $page,
					'zone'        => array_search($zone, $this->zones),
					'disposition' => serialize($disposition)
				));
			}
		}
		
		$this->db->insert('nf_settings_addons', array(
			'name'   => $this->_theme_name,
			'type'   => 'theme',
			'enable' => TRUE
		));

		return $this;
	}
	
	public function uninstall()
	{
		$widgets = array();
		
		foreach ($this->db->select('disposition')->from('nf_dispositions')->where('theme', $this->_theme_name)->get() as $disposition)
		{
			foreach (unserialize($disposition) as $rows)
			{
				foreach ($rows->cols as $col)
				{
					foreach ($col->widgets as $widget)
					{
						$widgets[] = $widget->widget_id;
					}
				}
			}
		}
		
		$this->db	->where('theme', $this->_theme_name)
					->delete('nf_dispositions');
		
		$this->db	->where('widget_id', $widgets)
					->delete('nf_widgets');
		
		$this->db	->where('name', $this->_theme_name)
					->where('type', 'theme')
					->delete('nf_settings_addons');
		
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/classes/theme.php
*/