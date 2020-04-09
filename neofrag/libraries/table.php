<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Table extends Library
{
	static protected $_table;

	private $_ajax          = FALSE;
	private $_pagination    = TRUE;
	private $_columns       = [];
	private $_data          = [];
	private $_sortings      = [];
	private $_preprocessing = [];
	private $_no_data       = '';
	private $_words         = [];

	public function __invoke()
	{
		if (!static::$_table)
		{
			static::$_table = $this;
			$this->id = $this->__id();
		}

		return static::$_table;
	}

	public function add_column($title, $content, $size = NULL, $search = NULL, $sort = NULL, $align = 'left')
	{
		$this->_columns[] = [
			'title'   => $title,
			'content' => $content,
			'size'    => $size,
			'search'  => $search,
			'sort'    => $sort,
			'align'   => $align
		];

		return $this;
	}

	public function add_columns($columns)
	{
		$this->_columns = array_merge($this->_columns, $columns);
		return $this;
	}

	public function sort_by($column_id, $order = SORT_ASC, $type = SORT_REGULAR)
	{
		if (is_integer($column_id) && in_array($order, [SORT_ASC, SORT_DESC, -1]) && in_array($type, [SORT_REGULAR, SORT_NUMERIC, SORT_STRING]))
		{
			if (isset($this->_sortings[$column_id - 1]))
			{
				unset($this->_sortings[$column_id - 1]);
			}

			$this->_sortings[$column_id - 1] = [$order, $type];
		}

		return $this;
	}

	public function data($data)
	{
		$this->_data = $data;
		return $this;
	}

	public function no_data($no_data)
	{
		$this->_no_data = $no_data;
		return $this;
	}

	public function pagination($pagination = TRUE)
	{
		$this->_pagination = $pagination;
		return $this;
	}

	public function preprocessing($callback)
	{
		$this->_preprocessing = $callback;
		return $this;
	}

	public function display()
	{
		NeoFrag()	->css('table')
							->js('table');

		$output = '';
		$search = trim(post('search'));

		if (post('table_id'))
		{
			if (post('table_id') == $this->id)
			{
				$this->_ajax = TRUE;
			}
			else
			{
				$this->save();
				return;
			}
		}

		if (($session_sort = $this->session('table', $this->id, 'sort')) !== NULL )
		{
			foreach ($session_sort as $session)
			{
				list($column_id, $order) = $session;
				$this->sort_by($column_id, $order);
			}
		}

		if ($this->_pagination && !empty($this->output->module()->pagination) && ($items_per_page = $this->session('table', $this->id, 'items_per_page')) !== NULL)
		{
			$this->output->module()->pagination->set_items_per_page($items_per_page);
		}

		if (($sort = post('sort')) !== NULL && $this->_ajax)
		{
			list($column_id, $order) = json_decode($sort);

			if (in_array($order, ['asc', 'desc', 'none']))
			{
				if ($order == 'asc')
				{
					$order = SORT_ASC;
				}
				else if ($order == 'desc')
				{
					$order = SORT_DESC;
				}
				else if ($order == 'none')
				{
					$order = -1;
				}

				$added = FALSE;

				if (($session_sort = $this->session('table', $this->id, 'sort')) !== NULL)
				{
					foreach ($session_sort as $i => $session)
					{
						if ($column_id == $session[0])
						{
							$added = TRUE;

							if ($order != -1 || isset($this->_sortings[$column_id]))
							{
								$this->session->set('table', $this->id, 'sort', $i, [$column_id, $order]);
							}
							else
							{
								$this->session->destroy('table', $this->id, 'sort', $i);
							}
						}
					}
				}

				if (!$added)
				{
					$this->session->append('table', $this->id, 'sort', [$column_id, $order]);
				}

				$this->sort_by($column_id, $order);
			}

			$search = $this->session('table', $this->id, 'search');
		}

		$count_results  = $this->_pagination && !empty($this->output->module()->pagination) ? $this->output->module()->pagination->count() : count($this->_data);

		if ($this->_is_searchable() && $search && $this->_pagination && !empty($this->output->module()->pagination))
		{
			$this->_data = $this->output->module()->pagination->display_all();
		}
		else if (!empty($this->_sortings) && $this->_pagination && !empty($this->output->module()->pagination) && (!isset($search) || !$search))
		{
			$this->_data = $this->output->module()->pagination->get_data();
		}

		$this->_preprocessing();

		//Gestion des recherches
		if ($this->_is_searchable())
		{
			if ($search)
			{
				$results = [];
				$words   = explode(' ', trim($search));

				foreach ($this->_data as $data_id => $data)
				{
					$found = 0;
					$data  = array_merge(['data_id' => $data_id], $data);

					foreach ($this->_columns as $value)
					{
						if (!isset($value['search']) || $value['search'] === NULL)
						{
							continue;
						}

						$value = $this->_parse($value['search'], $data);

						foreach ($words as $word)
						{
							if (in_string($word, $value, FALSE))
							{
								$found++;
							}
						}

						if ($found == count($words))
						{
							break;
						}
					}

					if ($found == count($words))
					{
						$results[] = $data;
					}
				}

				$this->session->set('table', $this->id, 'search', $search);

				$this->_data    = $results;
				$this->_no_data = NeoFrag()->lang('Aucun résultat ne correspond à la recherche');
			}
			else
			{
				$this->session->destroy('table', $this->id, 'search');
			}

			$words = [];

			foreach ($this->_data as $data_id => $data)
			{
				$data = array_merge(['data_id' => $data_id], $data);

				foreach ($this->_columns as $value)
				{
					if (!isset($value['search']) || $value['search'] === NULL)
					{
						continue;
					}

					$this->_words[] = $value = $this->_parse($value['search'], $data);
					$words[]        = '"'.$value.'"';
				}
			}
		}

		if (empty($this->_data))
		{
			$output = '<div class="clearfix"></div>'.($this->_no_data ?: NeoFrag()->lang('Il n\'y a rien ici pour le moment'));
		}
		else
		{
			if (!$this->_ajax && $this->_is_searchable())
			{
				$search_input = '	<div class="table-search float-left">
										<div class="form-group has-feedback">
											<input class="form-control" data-provide="typeahead" data-items="5" data-source="'.utf8_htmlentities('['.implode(', ', array_unique(array_filter($words))).']').'" type="text"'.(!empty($search) ? ' value="'.$search.'"' : '').' placeholder="'.NeoFrag()->lang('Rechercher').'" autocomplete="off" />
										</div>
									</div>';
			}

			//Gestion des tris
			if (!empty($this->_sortings))
			{
				$sortings = [];
				foreach ($this->_sortings as $column => $order)
				{
					if (!isset($this->_columns[$column]) || !isset($this->_columns[$column]['sort']) || $order[0] == -1)
					{
						continue;
					}

					$tmp = [];
					foreach ($this->_data as $data_id => $data)
					{
						$data = array_merge(['data_id' => $data_id], $data);
						$tmp[] = $this->_parse($this->_columns[$column]['sort'], $data);
					}

					$sortings[] = array_map('strtolower', $tmp);
					$sortings   = array_merge($sortings, $order);
				}

				$data = [];

				foreach ($this->_data as $key => $value)
				{
					$data[$key.' '] = $value;
				}

				$sortings[] = &$data;

				call_user_func_array('array_multisort', $sortings);

				$this->_data = [];

				foreach ($data as $key => $value)
				{
					$this->_data[trim($key)] = $value;
				}

				if ($this->_pagination && !empty($this->output->module()->pagination) && ($items_per_page = $this->output->module()->pagination->get_items_per_page()) > 0)
				{
					$this->_data = array_slice($this->_data, ($this->output->module()->pagination->get_page() - 1) * $items_per_page, $items_per_page);
				}
			}

			if ($this->_pagination && !empty($this->output->module()->pagination) && $this->output->module()->pagination->count() > 10)
			{
				$output .= '<div class="form-group float-left">
								<select class="form-control" style="width: auto;" onchange="window.location=\''.url($this->output->module()->pagination->get_url()).'/\'+$(this).find(\'option:selected\').data(\'url\')" autocomplete="off">
									<option value="10"'. ($this->output->module()->pagination->get_items_per_page() == 10  ? ' selected="selected"' : '').' data-url="page/1/10">'.NeoFrag()->lang('%d résultat|%d résultats', 10, 10).'</option>
									<option value="25"'. ($this->output->module()->pagination->get_items_per_page() == 25  ? ' selected="selected"' : '').' data-url="page/1/25">'.NeoFrag()->lang('%d résultat|%d résultats', 25, 25).'</option>
									<option value="50"'. ($this->output->module()->pagination->get_items_per_page() == 50  ? ' selected="selected"' : '').' data-url="page/1/50">'.NeoFrag()->lang('%d résultat|%d résultats', 50, 50).'</option>
									<option value="100"'.($this->output->module()->pagination->get_items_per_page() == 100 ? ' selected="selected"' : '').' data-url="page/1/100">'.NeoFrag()->lang('%d résultat|%d résultats', 100, 100).'</option>
									<option value="all"'.($this->output->module()->pagination->get_items_per_page() == 0   ? ' selected="selected"' : '').' data-url="all">'.NeoFrag()->lang('Tout afficher').'</option>
								</select>
							</div>';
			}

			if ($this->_pagination && !empty($this->output->module()->pagination) && ($pagination = $this->output->module()->pagination->get_pagination()))
			{
				$output .= $pagination;
			}

			$count = count($this->_data);

			$output .= '<table class="table table-hover table-striped">';

			if ($this->_display_header())
			{
				$output .= '<thead>';

				$header = '			<tr class="navbar-inner">';

				$i = 0;

				foreach ($this->_columns as $th)
				{
					$width = isset($th['size']) ? $th['size'] : FALSE;
					$class = [];
					$sort  = '';

					if ($width === TRUE)
					{
						$class[] = 'action';
					}

					if (!empty($this->_data) && isset($th['sort']))
					{
						$class[] = 'sort';
						$sort    = ' data-column="'.($i + 1).'"';

						if (isset($this->_sortings[$i]) && $this->_sortings[$i][0] == SORT_ASC)
						{
							$class[] = 'sorting_asc';
							$sort   .= ' data-order-by="desc"';
						}
						else if (isset($this->_sortings[$i]) && $this->_sortings[$i][0] == SORT_DESC)
						{
							$class[] = 'sorting_desc';
							$sort   .= ' data-order-by="none"';
						}
						else
						{
							$class[] = 'sorting';
							$sort   .= ' data-order-by="asc"';
						}
					}

					if (!empty($th['align']) && in_array($th['align'], ['left', 'center', 'right']))
					{
						$class[] = 'text-'.$th['align'];
					}

					$header .= '		<th'.(!empty($class) ? ' class="'.implode(' ', $class).'"' : '').(!is_bool($width) ? ' style="width: '.$width.';"' : '').(!empty($sort) ? $sort : '').'>'.(!empty($th['title']) ? $th['title'] : '').'</th>';

					$i++;
				}

				$header .= '		</tr>';

				$output .= 		$header.'
								</thead>';
			}

			$output .= '	<tbody>';

			foreach ($this->_data as $data_id => $data)
			{
				$data = array_merge(['data_id' => $data_id], $data);

				$output .= '<tr>';

				foreach ($this->_columns as $value)
				{
					if (is_array($value['content']))
					{
						$actions = [];

						foreach ($value['content'] as $val)
						{
							$actions[] = $this->_parse($val, $data);
						}

						$output .= '<td class="action">'.implode('&nbsp;', array_filter($actions)).'</td>';
					}
					else
					{
						$content = $this->_parse($value['content'], $data);

						if (!isset($value['td']) || $value['td'])
						{
							$classes = [];

							if (isset($value['size']) && $value['size'] === TRUE)
							{
								$classes[] = 'action';
							}

							if (!empty($value['class']))
							{
								$classes[] = $value['class'];
							}

							if (!empty($value['align']) && in_array($value['align'], ['left', 'center', 'right']))
							{
								$classes[] = 'text-'.$value['align'];
							}

							$content = '<td'.(!empty($classes) ? ' class="'.implode(' ', $classes).'"' : '').'>'.$content.'</td>';
						}

						$output .= $content;
					}
				}

				$output .= '</tr>';
			}

			$output .= '	</tbody>';

			if ($this->_pagination && !empty($this->output->module()->pagination) && $this->output->module()->pagination->get_items_per_page() >= 50 && $count >= 50)
			{
				$output .= '<tfoot>'.$header.'</tfoot>';
			}

			$output .= '</table>';

			if (!empty($pagination))
			{
				$output .= '<div class="float-right">'.$pagination.'</div>';
			}

			$output .= '<i>'.NeoFrag()->lang('%d résultat|%d résultats', $count, $count).($count < $count_results ? NeoFrag()->lang(' sur %d au total', $count_results) : '').'</i>';

			if (!$this->_ajax)
			{
				$output = '<div class="table-area" data-table-id="'.$this->id.'"'.($this->url->ajax ? ' data-ajax-url="'.url($this->url->request).'"  data-ajax-post="'.http_build_query(post()).'"' : '').'>'.(isset($search_input) ? $search_input : '').'<div class="table-content">'.$output.'</div></div>';
			}
		}

		$this->save();

		if ($this->_ajax)
		{
			return $this->output->json([
				'search'  => [],//array_values(array_unique($this->_words)),
				'content' => $output
			]);
		}
		else
		{
			return $output;
		}
	}

	public function save()
	{
		static::$_table = NULL;
		return $this;
	}

	private function _is_searchable()
	{
		foreach ($this->_columns as $value)
		{
			if (array_key_exists('search', $value))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	private function _display_header()
	{
		foreach ($this->_columns as $value)
		{
			if (array_key_exists('title', $value) || array_key_exists('sort', $value))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	private function _preprocessing()
	{
		if ($this->_preprocessing)
		{
			$this->_data = array_map($this->_preprocessing, $this->_data);
		}

		return $this;
	}

	private function _parse($content, $data = [])
	{
		if (is_a($content, 'closure'))
		{
			$content = call_user_func($content, $data);
		}

		return $content;
	}
}
