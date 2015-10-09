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

class Widget_View
{
	public function __construct($data)
	{
		foreach ($data as $var => $value)
		{
			$this->$var = $value;
		}
	}
	
	public function display($id = NULL)
	{
		static $widgets;

		if (is_null($widgets))
		{
			foreach (NeoFrag::loader()->db->from('nf_widgets')->get() as $widget)
			{
				$widgets[$widget['widget_id']] = $widget;
			}
		}
		
		$widget = $widgets[$this->widget_id];
		
		$output   = array();
		$instance = NeoFrag::loader()->widget($widget['widget']);
		$result   = $instance->get_output($widget['type'], is_array($settings = unserialize($widget['settings'])) ? $settings : array());
		
		foreach ($result as $result)
		{
			if (is_a($result, 'Panel'))
			{
				if (strlen($widget['title']))
				{
					$result->title = $widget['title'];
				}
				
				if (isset($this->style))
				{
					$result->style = $this->style;
				}
				
				$output[] = $result->display($id);
			}
			else
			{
				$output[] = $result;
			}
		}
		
		if ($widget['widget'] == 'module')
		{
			$type   = 'module';
			$module = NeoFrag::loader()->module;
			$name   = $module->name;
		}
		else
		{
			$type = 'widget';
			$name = $widget['widget'];
		}
		
		return '<div class="'.$type.' '.$type.'-'.$name.(!is_null($id) ? ' live-editor-widget" data-widget-id="'.$id.'" data-title="'.(!empty($module) ? $module->get_title() : $instance->get_title()).'"' : '"').'>'.implode($output).'</div>';
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/classes/widget_view.php
*/