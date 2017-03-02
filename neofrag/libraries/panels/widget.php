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

class Panel_widget extends Panel
{
	protected $_id;
	protected $_widget;

	public function __invoke($widget = 0)
	{
		return $this->reset()
					->widget_id($widget);
	}

	public function id($id)
	{
		$this->_id = $id;
		return $this;
	}

	public function widget_id($widget = NULL)
	{
		if (func_num_args())
		{
			$this->_widget = $widget;
			return $this;
		}
		else
		{
			return $this->_widget;
		}
	}

	public function __toString()
	{
		$widget_data = $this->db->from('nf_widgets')
								->where('widget_id', $this->_widget)
								->row();

		if ($widget_data && $widget = $this->widget($widget_data['widget']))
		{
			$output = implode(array_map(function($a) use ($widget_data){
				if (is_a($a, 'Panel'))
				{
					if (!empty($widget_data['title']))
					{
						$a->title($widget_data['title']);
					}

					if (!empty($this->_color))
					{
						$a->color($this->_color);
					}
				}

				return $a;
			}, $widget->get_output($widget_data['type'], is_array($settings = unserialize($widget_data['settings'])) ? $settings : [])));

			if ($widget_data['widget'] == 'module')
			{
				$type   = 'module';
				$module = $this->load->module;
				$name   = $module->name;
			}
			else
			{
				$type = 'widget';
				$name = $widget_data['widget'];
			}

			return '<div class="'.$type.' '.$type.'-'.$name.($this->_id !== NULL ? ' live-editor-widget" data-widget-id="'.$this->_id.'" data-title="'.$$type->get_title().'"' : '"').'>'.$output.'</div>';
		}

		return '';
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/panel_widget.php
*/