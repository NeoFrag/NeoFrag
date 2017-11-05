<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_live_editor_m_live_editor extends Model
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

		$f = function($d) use (&$f, &$widgets){
			if (!$d)
			{
				return;
			}

			if (method_exists($d, 'children'))
			{
				$d = $d->children();
			}
			else if (method_exists($d, 'widget_id'))
			{
				$widgets[] = $d->widget_id();
				return;
			}
			else if (!is_array($d))
			{
				$d = [$d];
			}

			array_walk($d, $f);
		};

		$f($disposition);

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
		foreach ($this->addons->get_widgets() as $widget)
		{
			if ($widget->name == 'error')
			{
				continue;
			}

			$widgets[$widget->name] = $widget->get_title();

			if (!empty($widget->types))
			{
				$types[$widget->name] = $widget->lang($widget->types, NULL);
				array_natsort($types[$widget->name]);
			}
		}

		array_natsort($widgets);
	}
}
