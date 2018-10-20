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

	abstract public function styles_row();
	abstract public function styles_widget();

	public function __init()
	{

	}

	public function is_enabled()
	{
		return $this->config->nf_default_theme == $this->info()->name;
	}

	public function is_deactivatable()
	{
		return parent::is_deactivatable() && NeoFrag()->model2('addon')->get('theme')->count() > 2;
	}

	public function install($dispositions = [])
	{
		foreach ($this->info()->zones as $zone)
		{
			if ($dispositions->get('*', $zone) === NULL)
			{
				$dispositions->set('*', $zone, $this->array());
			}
		}

		foreach ($dispositions as $page => $dispositions)
		{
			foreach ($dispositions as $zone => $disposition)
			{
				$this->db->insert('nf_dispositions', [
					'theme'       => $this->info()->name,
					'page'        => $page,
					'zone'        => array_search($zone, $this->info()->zones),
					'disposition' => serialize($disposition)
				]);
			}
		}

		$this->module('tools')->api()->scss();

		return parent::install();
	}

	public function uninstall($remove = TRUE)
	{
		if ($dispositions = $this->db->select('disposition')->from('nf_dispositions')->where('theme', $this->info()->name)->get())
		{
			$this->module('live_editor')->model()->delete_widgets($this->array($dispositions)->each('unserialize'));

			$this->db	->where('theme', $this->info()->name)
						->delete('nf_dispositions');
		}

		return parent::uninstall($remove);
	}
}
