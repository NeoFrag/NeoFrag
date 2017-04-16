<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor\Models;

use NF\NeoFrag\Loadables\Model;

class Live_Editor extends Model
{
	public function get_disposition($disposition_id, &$theme, &$page, &$zone)
	{
		$disposition = $this->db	->select('disposition', 'theme', 'page', 'zone')
									->from('nf_dispositions')
									->where('disposition_id', $disposition_id)
									->row();

		$theme = $disposition['theme'];
		$page  = $disposition['page'];
		$zone  = $disposition['zone'];

		return unserialize($disposition['disposition']);
	}

	public function set_disposition($disposition_id, $disposition)
	{
		$this->db	->where('disposition_id', $disposition_id)
					->update('nf_dispositions', [
						'disposition' => serialize($disposition)
					]);
	}

	public function delete_widgets($disposition)
	{
		$widgets = [];

		$disposition->each($f = function($a) use (&$f, &$widgets){
			if (is_a($a, 'NF\NeoFrag\Displayables\Widget'))
			{
				$widgets[] = $a->widget_id();
			}
			else if ($a)
			{
				$a->each($f);
			}
		});

		if ($widgets)
		{
			$this->db	->where('widget_id', $widgets)
						->delete('nf_widgets');
		}
	}

	public function check_widget($widget_id)
	{
		$widget = $this->db	->from('nf_widgets')
							->where('widget_id', $widget_id)
							->row();

		if ($widget)
		{
			if ($widget['settings'] !== NULL)
			{
				$widget['settings'] = serialize($widget['settings']);
			}

			return $widget;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_widgets(&$widgets, &$types)
	{
		foreach (NeoFrag()->model2('addon')->get('widget') as $widget)
		{
			$widgets[$name = $widget->info()->name] = $widget->info()->title;

			if (!empty($widget->info()->types))
			{
				$types[$name] = $widget->info()->types;
				array_natsort($types[$name]);
			}
		}

		array_natsort($widgets);
	}
}
