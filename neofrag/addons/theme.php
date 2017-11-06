<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Addons;

use NF\NeoFrag\Loadables\Addon;

abstract class Theme extends Addon
{
	static public $core = [
		'admin'   => FALSE,
		'default' => TRUE
	];

	static public function __class($name)
	{
		return 'Themes\\'.$name.'\\'.$name;
	}

	static public function __label()
	{
		return ['Thèmes', 'Thème', 'fa-tint', 'success'];
	}

	abstract public function styles_row();
	abstract public function styles_widget();

	public function __actions()
	{
		return [
			['enable',   'Activer',       'fa-check',   'success'],
			['disable',  'Désactiver',    'fa-times',   'muted'],
			['settings', 'Configuration', 'fa-wrench',  'warning'],
			NULL,
			['reset',    'Réinitialiser', 'fa-refresh', 'danger'],
			['delete',   'Désinstaller',  'fa-remove',  'danger']
		];
	}

	public function __init()
	{

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
					'zone'        => array_search($zone, $this->info()->zones),
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
