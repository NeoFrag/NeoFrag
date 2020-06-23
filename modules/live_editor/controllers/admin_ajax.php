<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	public function zone_fork($disposition_id, $disposition, $url, $theme, $page, $zone)
	{
		$url = ltrim(preg_replace('_^'.$this->url().'_', '', $url), '/');

		if (!$url || preg_match('#^index(/.*)?$#', $url))
		{
			$url = '/';
		}
		else
		{
			$url = explode('/', $url.'/*');

			if (!empty($url[0]))
			{
				if ($module = $this->module($url[0]))
				{
					if (!empty($module->info()->routes) && ($method = @$module->get_method(array_slice($url, 1, -1), TRUE)))
					{
						$url = [$url[0], $method, '*'];
					}
				}
				else if ($module = $this->module('pages'))
				{
					$url = [$url[0], '*'];
				}
			}

			$url = implode('/', $url);
		}

		if ($page == '*' || $this->db->from('nf_dispositions')->where('page', $url)->empty())
		{
			$traversal = function($array) use (&$traversal){
				$array->each(function($a) use (&$traversal){
					if (is_a($a, 'NF\NeoFrag\Libraries\Array_') && !is_a($a, 'NF\NeoFrag\Displayables\Widget'))
					{
						$traversal($a);
					}
					else
					{
						$a->widget_id($this->db->insert('nf_widgets', $this->db	->select('widget', 'type', 'title', 'settings')
																				->from('nf_widgets')
																				->where('widget_id', $a->widget_id())
																				->row()));
					}

					return $a;
				});
			};

			$traversal($disposition);

			$id = $this->db->insert('nf_dispositions', $disposition = [
				'theme'       => $theme,
				'page'        => $url,
				'zone'        => $zone,
				'disposition' => serialize($disposition)
			]);

			$disposition['disposition_id'] = $id;

			return $this->zone()->display($disposition);
		}
		else
		{
			$this->model()->delete_widgets($disposition);

			$this->db	->where('disposition_id', $disposition_id)
						->delete('nf_dispositions');

			$disposition = $this->db->from('nf_dispositions')
									->where('theme', $theme)
									->where('page', '*')
									->where('zone', $zone)
									->row();

			return $this->zone()->display($disposition);
		}
	}

	public function row_add($disposition_id, $disposition)
	{
		$row = $disposition[] = $this->row()->style('row-default');
		$this->model()->set_disposition($disposition_id, $disposition);

		return $row->id($disposition->last_key());
	}

	public function row_move($disposition_id, $disposition, $row_id, $position)
	{
		$this->model()->set_disposition($disposition_id, $disposition->move($row_id, $position));
	}

	public function row_style($disposition_id, $disposition, $row_id, $style)
	{
		$disposition[$row_id]->style($style);
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function row_delete($disposition_id, $disposition, $row_id)
	{
		$this->model()->delete_widgets($disposition[$row_id]);
		unset($disposition[$row_id]);
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function col_add($disposition_id, $disposition, $row_id)
	{
		$disposition[$row_id]->append($col = $this->col()->size('col-4'));
		$this->model()->set_disposition($disposition_id, $disposition);

		return $col->id($disposition[$row_id]->last_key());
	}

	public function col_move($disposition_id, $disposition, $row_id, $col_id, $position)
	{
		$disposition[$row_id]->move($col_id, $position);
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function col_size($disposition_id, $disposition, $row_id, $col_id, $size)
	{
		$disposition[$row_id][$col_id]->size('col-'.min(12, max($size, 1)));
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function col_delete($disposition_id, $disposition, $row_id, $col_id)
	{
		$this->model()->delete_widgets($disposition[$row_id][$col_id]);
		$disposition[$row_id]->destroy($col_id);
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function widget_add($disposition_id, $disposition, $row_id, $col_id, $title, $widget_name, $type, $settings)
	{
		$widget_id = $this->db	->insert('nf_widgets', [
									'title'    => $title ? utf8_htmlentities($title) : NULL,
									'widget'   => $widget_name,
									'type'     => $type,
									'settings' => $this->widget($widget_name)->get_settings($type, $settings)
								]);

		$disposition[$row_id][$col_id]->append($widget = $this->widget($widget_id));

		$this->model()->set_disposition($disposition_id, $disposition);

		return $widget->id($disposition[$row_id][$col_id]->last_key());
	}

	public function widget_move($disposition_id, $disposition, $row_id, $col_id, $widget_id, $position)
	{
		$disposition[$row_id][$col_id]->move($widget_id, $position);
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function widget_admin($widget_name, $type, $settings = [])
	{
		return implode($this->widget($widget_name)->get_admin($type, $settings));
	}

	public function widget_style($disposition_id, $disposition, $row_id, $col_id, $widget_id, $style)
	{
		$disposition[$row_id][$col_id][$widget_id]->style($style);
		$this->model()->set_disposition($disposition_id, $disposition);
	}

	public function widget_settings($widget_id = 0, $widget = '', $type = 'index', $title = '', $settings = '')
	{
		$this->model()->get_widgets($widgets, $types);

		return $this->view('widget', [
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
		$settings = $this->widget($widget)->get_settings($type, $settings);

		$this->db	->where('widget_id', $id)
					->update('nf_widgets', [
						'title'    => $title ? utf8_htmlentities($title) : NULL,
						'widget'   => $widget,
						'type'     => $type,
						'settings' => $settings
					]);

		return $disposition[$row_id][$col_id][$widget_id]->id($widget_id);
	}

	public function widget_delete($disposition_id, $disposition, $row_id, $col_id, $widget_id)
	{
		$this->model()->delete_widgets($disposition[$row_id][$col_id][$widget_id]);
		$disposition[$row_id][$col_id]->destroy($widget_id);
		$this->model()->set_disposition($disposition_id, $disposition);
	}
}
