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

abstract class Theme extends Loadable
{
	static public $core = [
		'admin'   => FALSE,
		'default' => TRUE
	];

	abstract public function styles_row();
	abstract public function styles_widget();

	public $styles;
	
	public function paths()
	{
		return [
			'assets' => [
				'overrides/themes/'.$this->name,
				'neofrag/themes/'.$this->name,
				'themes/'.$this->name
			],
			'controllers' => [
				'overrides/themes/'.$this->name.'/controllers',
				'neofrag/themes/'.$this->name.'/controllers',
				'themes/'.$this->name.'/controllers'
			],
			'forms' => [
				'overrides/themes/'.$this->name.'/forms',
				'neofrag/themes/'.$this->name.'/forms',
				'themes/'.$this->name.'/forms'
			],
			'helpers' => [
				'overrides/themes/'.$this->name.'/helpers',
				'neofrag/themes/'.$this->name.'/helpers',
				'themes/'.$this->name.'/helpers'
			],
			'lang' => [
				'overrides/themes/'.$this->name.'/lang',
				'neofrag/themes/'.$this->name.'/lang',
				'themes/'.$this->name.'/lang'
			],
			'libraries' => [
				'overrides/themes/'.$this->name.'/libraries',
				'neofrag/themes/'.$this->name.'/libraries',
				'themes/'.$this->name.'/libraries'
			],
			'models' => [
				'overrides/themes/'.$this->name.'/models',
				'neofrag/themes/'.$this->name.'/models',
				'themes/'.$this->name.'/models'
			],
			'views' => [
				'overrides/themes/'.$this->name.'/views',
				'neofrag/themes/'.$this->name.'/views',
				'themes/'.$this->name.'/overrides/views',
				'themes/'.$this->name.'/views'
			]
		];
	}
	
	public function load()
	{
		if ($this->name != 'default')
		{
			array_unshift(NeoFrag::loader()->paths['assets'], 'themes/'.$this->name);
			array_unshift(NeoFrag::loader()->paths['views'],  'themes/'.$this->name.'/overrides/views', 'themes/'.$this->name.'/views');
		}
		
		return $this;
	}
	
	public function install($dispositions = [])
	{
		foreach ($dispositions as $page => $dispositions)
		{
			foreach ($dispositions as $zone => $disposition)
			{
				$this->db->insert('nf_dispositions', [
					'theme'       => $this->name,
					'page'        => $page,
					'zone'        => array_search($zone, $this->zones),
					'disposition' => serialize($disposition)
				]);
			}
		}

		return parent::install();
	}
	
	public function uninstall($remove = TRUE)
	{
		$widgets = [];
		
		foreach ($this->db->select('disposition')->from('nf_dispositions')->where('theme', $this->name)->get() as $disposition)
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
		
		$this->db	->where('theme', $this->name)
					->delete('nf_dispositions');
		
		$this->db	->where('widget_id', $widgets)
					->delete('nf_widgets');
		
		return parent::uninstall($remove);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/theme.php
*/