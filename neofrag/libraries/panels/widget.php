<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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

					if (!empty($this->_style))
					{
						$a->style($this->_style);
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
