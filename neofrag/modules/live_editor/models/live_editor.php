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
					->update('nf_dispositions', array(
						'disposition' => serialize($disposition)
					));
	}
	
	public function delete_disposition($rows)
	{
		foreach ($rows as $row)
		{
			$this->delete_row($row->cols);
		}

		return $this;
	}
	
	public function delete_row($cols)
	{
		foreach ($cols as $col)
		{
			$this->delete_col($col->widgets);
		}

		return $this;
	}
	
	public function delete_col($widgets)
	{
		foreach ($widgets as $widget)
		{
			$this->delete_widget($widget->widget_id);
		}

		return $this;
	}
	
	public function delete_widget($widget_id)
	{
		$this->db	->where('widget_id', $widget_id)
					->delete('nf_widgets');
	}
	
	public function check_widget($widget_id)
	{
		$widget = $this->db	->from('nf_widgets')
							->where('widget_id', $widget_id)
							->row();
							
		if ($widget)
		{
			if (!is_null($widget['settings']))
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
		foreach (array_keys($this->addons('widget')) as $widget_name)
		{
			$w = $this->load->widget($widget_name);
			
			$widgets[$widget_name] = $w->get_title();
			
			if (!empty($w->types))
			{
				$types[$widget_name] = $w->load->lang($w->types, NULL);
				natsort($types[$widget_name]);
			}
		}
		
		natsort($widgets);
		natsort($types);
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/live_editor/models/live_editor.php
*/