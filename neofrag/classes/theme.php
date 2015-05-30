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
				'lang' => array(
					'./overrides/themes/'.$theme_name.'/lang',
					'./neofrag/themes/'.$theme_name.'/lang',
					'./themes/'.$theme_name.'/lang'
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
}

/*
NeoFrag Alpha 0.1
./neofrag/classes/theme.php
*/