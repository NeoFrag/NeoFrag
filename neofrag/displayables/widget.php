<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Displayables;

use NF\NeoFrag\Displayable;

class Widget extends Displayable
{
	protected $_id;
	protected $_widget;
	protected $_style;
	protected $_size;

	public function __invoke($widget = 0)
	{
		return is_int($widget) ? $this->widget_id($widget) : forward_static_call_array('NF\NeoFrag\Addons\Widget::__load', [NeoFrag(), func_get_args()]);
	}

	public function __sleep()
	{
		return ['_widget', '_style', '_size'];
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

	public function style($style)
	{
		$this->_style = $style;
		return $this;
	}

	public function size($size = '')
	{
		if (func_num_args())
		{
			$this->_size = $size;
			return $this;
		}
		else
		{
			return $this->_size;
		}
	}

	public function __toString()
	{
		$widget_data = NeoFrag()->db->from('nf_widgets')
									->where('widget_id', $this->_widget)
									->row();

		if ($widget_data && ($widget = NeoFrag()->widget($widget_data['widget'])) && $widget->is_enabled())
		{
			$widget->data = NeoFrag()->array;

			/*$output = implode(array_map(function($a) use ($widget_data){
				if (is_a($a, 'NF\NeoFrag\Libraries\Panel'))
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
			}, );*/

			$output = $widget->output($widget_data['type'], is_array($settings = unserialize($widget_data['settings'])) ? $settings : []);

			if ($widget_data['widget'] == 'module')
			{
				$type   = 'module';
				$module = NeoFrag()->output->module();
				$name   = $module->info()->name;
			}
			else
			{
				$type = 'widget';
				$name = $widget_data['widget'];
			}

			return '<div class="'.$type.' '.$type.'-'.$name.($this->_id !== NULL ? ' live-editor-widget" data-widget-id="'.$this->_id.'" data-title="'.$$type->info()->title.'"' : '"').'>'.$output.'</div>';
		}

		return '';
	}
}
