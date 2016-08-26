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

class m_live_editor_c_ajax extends Controller_Module
{
	public function zone_fork($disposition_id, $disposition, $url, $theme, $page, $zone)
	{
		$url = str_replace(url(), '', $url);
		
		if (!$url || preg_match('#^index(?:\.|/)#', $url))
		{
			$url = '/';
		}
		
		if (extension($url) == 'html')
		{
			$url = substr($url, 0, -5).'/*';
		}
		
		$url = explode('/', $url);
		
		if (!empty($url[0]) && ($module = $this->load->module($url[0])) && !empty($module->routes) && ($method = $module->get_method(array_slice($url, 1, -1), TRUE)))
		{
			$url = [$url[0], $method, '*'];
		}
		
		$url = implode('/', $url);
		
		if ($page == '*' || !$this->db->select('1')->from('nf_dispositions')->where('page', $url)->row())
		{
			foreach ($disposition as &$rows)
			{
				foreach ($rows->cols as &$col)
				{
					foreach ($col->widgets as &$w)
					{
						$w->widget_id = $this->db->insert('nf_widgets', $this->db	->select('widget', 'type', 'title', 'settings')
																					->from('nf_widgets')
																					->where('widget_id', $w->widget_id)
																					->row());
					}
				}
			}
			
			return Zone::display($this->db->insert('nf_dispositions', [
				'theme'       => $theme,
				'page'        => $url,
				'zone'        => $zone,
				'disposition' => serialize($disposition)
			]), $disposition, $url, $zone, TRUE);
		}
		else
		{
			$this->model()->delete_disposition($disposition);
			
			$this->db	->where('disposition_id', $disposition_id)
						->delete('nf_dispositions');
			
			$disposition = $this->db->select('disposition_id', 'disposition')
									->from('nf_dispositions')
									->where('theme', $theme)
									->where('page', '*')
									->where('zone', $zone)
									->row();
			
			return Zone::display($disposition['disposition_id'], unserialize($disposition['disposition']), '*', $zone, TRUE);
		}
	}
	
	public function row_add($disposition_id, $disposition)
	{
		$row = $disposition[] = new Row('row-default');
		$this->model()->set_disposition($disposition_id, $disposition);
		
		return $row->display(array_last_key($disposition));
	}
	
	public function row_move($disposition_id, $disposition, $row_id, $position)
	{
		$row = $disposition[$row_id];
		unset($disposition[$row_id]);
		$this->model()->set_disposition($disposition_id, array_slice($disposition, 0, $position, TRUE) + [$row_id => $row] + array_slice($disposition, $position, NULL, TRUE));
	}
	
	public function row_style($disposition_id, $disposition, $row_id, $style)
	{
		$disposition[$row_id]->style = $style;
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function row_delete($disposition_id, $disposition, $row_id)
	{
		$this->model()->delete_row($disposition[$row_id]->cols);
		unset($disposition[$row_id]);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function col_add($disposition_id, $disposition, $row_id)
	{
		$col = $disposition[$row_id]->cols[] = new Col('col-md-4');
		$this->model()->set_disposition($disposition_id, $disposition);
		
		return $col->display(array_last_key($disposition[$row_id]->cols));
	}
	
	public function col_move($disposition_id, $disposition, $row_id, $col_id, $position)
	{
		$col = $disposition[$row_id]->cols[$col_id];
		unset($disposition[$row_id]->cols[$col_id]);
		$disposition[$row_id]->cols = array_slice($disposition[$row_id]->cols, 0, $position, TRUE) + [$col_id => $col] + array_slice($disposition[$row_id]->cols, $position, NULL, TRUE);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function col_size($disposition_id, $disposition, $row_id, $col_id, $size)
	{
		$disposition[$row_id]->cols[$col_id]->set_size($size);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function col_delete($disposition_id, $disposition, $row_id, $col_id)
	{
		$this->model()->delete_col($disposition[$row_id]->cols[$col_id]->widgets);
		$disposition[$row_id]->delete_col($col_id);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function widget_add($disposition_id, $disposition, $row_id, $col_id, $title, $widget_name, $type, $settings)
	{
		$widget_id = $this->db	->insert('nf_widgets', [
									'title'    => $title ? utf8_htmlentities($title) : NULL,
									'widget'   => $widget_name,
									'type'     => $type,
									'settings' => $this->load->widget($widget_name)->get_settings($type, $settings)
								]);
		
		$widget = $disposition[$row_id]->cols[$col_id]->widgets[] = new Widget_View([
			'widget_id' => $widget_id
		]);
		
		$this->model()->set_disposition($disposition_id, $disposition);
		
		return $widget->display(array_last_key($disposition[$row_id]->cols[$col_id]->widgets));
	}
	
	public function widget_move($disposition_id, $disposition, $row_id, $col_id, $widget_id, $position)
	{
		$widget = $disposition[$row_id]->cols[$col_id]->widgets[$widget_id];
		unset($disposition[$row_id]->cols[$col_id]->widgets[$widget_id]);
		$disposition[$row_id]->cols[$col_id]->widgets = array_slice($disposition[$row_id]->cols[$col_id]->widgets, 0, $position, TRUE) + [$widget_id => $widget] + array_slice($disposition[$row_id]->cols[$col_id]->widgets, $position, NULL, TRUE);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function widget_admin($widget_name, $type, $settings = NULL)
	{
		return $this->load->widget($widget_name)->get_admin($type, $settings);
	}
	
	public function widget_style($disposition_id, $disposition, $row_id, $col_id, $widget_id, $style)
	{
		$disposition[$row_id]->cols[$col_id]->widgets[$widget_id]->style = $style;
		$this->model()->set_disposition($disposition_id, $disposition);
	}
	
	public function widget_settings($widget_id = 0, $widget = '', $type = 'index', $title = '', $settings = '')
	{
		$this->model()->get_widgets($widgets, $types);

		return $this->load->view('widget', [
			'widget_id' => $widget_id,
			'title'     => $title,
			'widget'    => $widget ?: array_keys($widgets)[0],
			'widgets'   => $widgets,
			'type'      => $type,
			'types'     => $types
		]);
	}
	
	public function widget_update($disposition_id, $disposition, $row_id, $col_id, $widget_id, $id, $widget, $type, $title, $settings)
	{
		$settings = $this->load->widget($widget)->get_settings($type, $settings);
		
		$this->db	->where('widget_id', $id)
					->update('nf_widgets', [
						'title'    => $title ? utf8_htmlentities($title) : NULL,
						'widget'   => $widget,
						'type'     => $type,
						'settings' => $settings,
					]);

		return $disposition[$row_id]->cols[$col_id]->widgets[$widget_id]->display($widget_id);
	}
	
	public function widget_delete($disposition_id, $disposition, $row_id, $col_id, $widget_id)
	{
		$this->model()->delete_widget($disposition[$row_id]->cols[$col_id]->widgets[$widget_id]->widget_id);
		$disposition[$row_id]->cols[$col_id]->delete_widget($widget_id);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/live_editor/controllers/ajax.php
*/