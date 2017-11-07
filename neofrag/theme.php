<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
		if ($dispositions = $this->db->select('disposition')->from('nf_dispositions')->where('theme', $this->name)->get())
		{
			$this->module('live_editor')->model()->delete_widgets(array_map('unserialize', $dispositions));

			$this->db	->where('theme', $this->name)
						->delete('nf_dispositions');
		}

		return parent::uninstall($remove);
	}
}
