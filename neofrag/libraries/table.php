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

class Table extends Library
{
	private $_ajax          = FALSE;
	private $_pagination    = TRUE;
	private $_actions       = array();
	private $_columns       = array();
	private $_data          = array();
	private $_sortings      = array();
	private $_preprocessing = array();
	private $_no_data       = '';
	private $_words         = array();

	public function add_column($title, $content, $size = NULL, $search = NULL, $sort = NULL, $align = 'left')
	{
		$this->_columns[] = array(
			'title'   => $title,
			'content' => $content,
			'size'    => $size,
			'search'  => $search,
			'sort'    => $sort,
			'align'   => $align
		);

		return $this;
	}

	public function add_columns($columns)
	{
		$this->_columns = array_merge($this->_columns, $columns);
		return $this;
	}

	public function add_action($action)
	{
		$this->_actions[] = $action;
		return $this;
	}

	public function sort_by($column_id, $order = SORT_ASC, $type = SORT_REGULAR)
	{
		if (is_integer($column_id) && in_array($order, array(SORT_ASC, SORT_DESC, -1)) && in_array($type, array(SORT_REGULAR, SORT_NUMERIC, SORT_STRING)))
		{
			if (isset($this->_sortings[$column_id - 1]))
			{
				unset($this->_sortings[$column_id - 1]);
			}

			$this->_sortings[$column_id - 1] = array($order, $type);
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
		NeoFrag::loader()	->css('neofrag.table')
							->js('neofrag.table');

		$output = '';
		$search = trim(post('search'));

		if (post('table_id'))
		{
			if (post('table_id') == $this->id)
			{
				$this->config->ajax_url = TRUE;
				$this->_ajax            = TRUE;
			}
			else
			{
				$this->reset();
				return;
			}
		}

		if (!is_null($session_sort = $this->session('table', $this->id, 'sort')))
		{
			foreach ($session_sort as $session)
			{
				list($column_id, $order) = $session;
				$this->sort_by($column_id, $order);
			}
		}

		if ($this->_pagination && !empty($this->pagination) && !is_null($items_per_page = $this->session('table', $this->id, 'items_per_page')))
		{
			$this->pagination->set_items_per_page($items_per_page);
		}

		if (!is_null($sort = post('sort')) && $this->_ajax)
		{
			list($column_id, $order) = json_decode($sort);
			
			if (in_array($order, array('asc', 'desc', 'none')))
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
				
				if (!is_null($session_sort = $this->session('table', $this->id, 'sort')))
				{
					foreach ($session_sort as $i => $session)
					{
						if ($column_id == $session[0])
						{
							$added = TRUE;

							if ($order != -1 || isset($this->_sortings[$column_id]))
							{
								$this->session->set('table', $this->id, 'sort', $i, array($column_id, $order));
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
					$this->session->add('table', $this->id, 'sort', array($column_id, $order));
				}

				$this->sort_by($column_id, $order);
			}

			$search = $this->session('table', $this->id, 'search');
		}
		
		$count_results  = $this->_pagination && !empty($this->pagination) ? $this->pagination->count() : count($this->_data);
		
		if ($this->_is_searchable() && $search && $this->_pagination && !empty($this->pagination))
		{
			$this->_data = $this->pagination->display_all();
		}
		else if (!empty($this->_sortings) && $this->_pagination && !empty($this->pagination) && (!isset($search) || !$search))
		{
			$this->_data = $this->pagination->get_data();
		}
		
		$this->_preprocessing();

		//Gestion des recherches
		if ($this->_is_searchable())
		{
			if ($search)
			{
				$results = array();
				$words   = explode(' ', trim($search));

				foreach ($this->_data as $data_id => $data)
				{
					$found = 0;
					$data  = array_merge(array('data_id' => $data_id), $data);

					foreach ($this->_columns as $value)
					{
						if (!isset($value['search']) || is_null($value['search']))
						{
							continue;
						}

						$value = $this->template->parse($value['search'], $data, $this->load);

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
				$this->_no_data = 'Aucun résultat ne correspond à la recherche';
			}
			else
			{
				$this->session->destroy('table', $this->id, 'search');
			}

			$words = array();

			foreach ($this->_data as $data_id => $data)
			{
				$data = array_merge(array('data_id' => $data_id), $data);
				
				foreach ($this->_columns as $value)
				{
					if (!isset($value['search']) || is_null($value['search']))
					{
						continue;
					}

					$this->_words[] = $value = $this->template->parse($value['search'], $data, $this->load);
					$words[]        = '"'.$value.'"';
				}
			}
		}

		if (empty($this->_data))
		{
			$output = $this->_no_data ?: 'Il n\'y a rien ici pour le moment';
		}
		else
		{
			if (!$this->_ajax && $this->_is_searchable())
			{
				$search_input = '	<div class="table-search pull-left">
										<div class="form-group has-feedback">
											<input class="form-control table-search-input" data-provide="typeahead" data-items="5" data-source="'.utf8_htmlentities('['.trim_word(implode(', ', array_unique($words)), ', ').']').'" type="text"'.((isset($search)) ? ' value="'.$search.'"' : '').' placeholder="Rechercher..." autocomplete="off" />
										</div>
									</div>';
			}

			//Gestion des tris
			if (!empty($this->_sortings))
			{
				$sortings = array();
				foreach ($this->_sortings as $column => $order)
				{
					if (!isset($this->_columns[$column]) || !isset($this->_columns[$column]['sort']) || $order[0] == -1)
					{
						continue;
					}

					$tmp = array();
					foreach ($this->_data as $data_id => $data)
					{
						$data = array_merge(array('data_id' => $data_id), $data);
						$tmp[] = $this->template->parse($this->_columns[$column]['sort'], $data, $this->load);
					}

					$sortings[] = array_map('strtolower', $tmp);
					$sortings   = array_merge($sortings, $order);
				}

				$data = array();
				
				foreach ($this->_data as $key => $value)
				{
					$data[$key.' '] = $value;
				}
				
				$sortings[] = &$data;

				call_user_func_array('array_multisort', $sortings);
				
				$this->_data = array();
				
				foreach ($data as $key => $value)
				{
					$this->_data[trim($key)] = $value;
				}

				if ($this->_pagination && !empty($this->pagination) && ($items_per_page = $this->pagination->get_items_per_page()) > 0)
				{
					$this->_data = array_slice($this->_data, ($this->pagination->get_page() - 1) * $items_per_page, $items_per_page);
				}
			}

			if ($this->_pagination && !empty($this->pagination) && $this->pagination->count() > 10)
			{
				$output .= '<div class="pull-left" style="margin-bottom: 15px;">
								<select class="form-control" style="width: auto;" onchange="window.location=\''.url($this->pagination->get_url()).'/\'+$(this).find(\'option:selected\').data(\'url\')+\'.html\'" autocomplete="off">
									<option value="10"'. ($this->pagination->get_items_per_page() == 10  ? ' selected="selected"' : '').' data-url="page/1/10">10 résultats</option>
									<option value="25"'. ($this->pagination->get_items_per_page() == 25  ? ' selected="selected"' : '').' data-url="page/1/25">25 résultats</option>
									<option value="50"'. ($this->pagination->get_items_per_page() == 50  ? ' selected="selected"' : '').' data-url="page/1/50">50 résultats</option>
									<option value="100"'.($this->pagination->get_items_per_page() == 100 ? ' selected="selected"' : '').' data-url="page/1/100">100 résultats</option>
									<option value="all"'.($this->pagination->get_items_per_page() == 0   ? ' selected="selected"' : '').' data-url="all">Tout afficher</option>
								</select>
							</div>';
			}

			if ($this->_pagination && !empty($this->pagination) && ($pagination = $this->pagination->get_pagination()) != '')
			{
				$output .= $pagination;
			}

			$count = count($this->_data);
			
			$output .= '<table class="table table-hover table-striped">';
		
			if ($this->_display_header())
			{
				$output .= '<thead>';

				$header = '			<tr class="navbar-inner">';

				if ($count > 1 && !empty($this->_actions))
				{
					array_unshift($this->_columns, array(
						'title' => '<input class="table-checkbox" type="checkbox" data-toggle="tooltip" title="Sélectionner toutes les lignes" autocomplete="off" />',
						'content' => '<input class="table-checkbox" type="checkbox" autocomplete="off" />',
						'size' => TRUE
					));
					
					$i = -1;
				}
				else
				{
					$i = 0;
				}

				foreach ($this->_columns as $th)
				{
					$width = isset($th['size']) ? $th['size'] : FALSE;
					$class = array();
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

					$header .= '		<th'.(is_array($th['content']) && !empty($this->_data) ? ' colspan="'.count($th['content']).'"' : '').(!empty($class) ? ' class="'.implode(' ', $class).'"' : '').(!is_bool($width) ? ' style="width: '.$width.';"' : '').(!empty($sort) ? $sort : '').'>'.(!empty($th['title']) ? $th['title'] : '').'</th>';

					$i++;
				}

				$header .= '		</tr>';

				$output .= 		$header.'
								</thead>';
			}
			
			$output .= '	<tbody>';

			foreach ($this->_data as $data_id => $data)
			{
				$data = array_merge(array('data_id' => $data_id), $data);

				$output .= '<tr>';

				foreach ($this->_columns as $value)
				{
					if (is_array($value['content']))
					{
						foreach ($value['content'] as $val)
						{
							$output .= '<td class="action">'.$this->template->parse($val, $data, $this->load).'</td>';
						}
					}
					else
					{
						$classes = array();
						
						if (isset($value['size']) && $value['size'] === TRUE)
						{
							$classes[] = 'action';
						}
						
						if (!empty($value['class']))
						{
							$classes[] = $value['class'];
						}
						
						if (!empty($value['align']) && in_array($value['align'], array('left', 'center', 'right')))
						{
							$classes[] = 'text-'.$value['align'];
						}

						$output .= '<td'.(!empty($classes) ? ' class="'.implode(' ', $classes).'"' : '').'>'.$this->template->parse($value['content'], $data, $this->load).'</td>';
					}
				}

				$output .= '</tr>';
			}

			$output .= '	</tbody>';

			if ($this->_pagination && !empty($this->pagination) && $this->pagination->get_items_per_page() >= 50 && $count >= 50)
			{
				$output .= '<tfoot>'.$header.'</tfoot>';
			}

			$output .= '</table>';

			if ($count > 1 && !empty($this->_actions))
			{
				$output .= '<div class="table-actions">';

				foreach ($this->_actions as $action)
				{
					$output .= $action;
				}

				$output .= '</div>';
			}

			if (!empty($pagination))
			{
				$output .= $pagination;
			}

			$output .= '<i>'.$count.' '.($count > 1 ? 'résultats' : 'résultat').($count < $count_results ? ' sur '.$count_results.' au total' : '').'</i>';

			if (!$this->_ajax)
			{
				$output = '<div class="table-area" data-table-id="'.$this->id.'">'.(isset($search_input) ? $search_input : '').'<div class="table-content">'.$output.'</div></div>';
			}
		}
		
		$this->reset();

		if ($this->_ajax)
		{
			$this->session->save();
			echo $output;
			return '';
		}
		else
		{
			return $output;
		}
	}

	public function is_ajax()
	{
		return $this->config->ajax_url && post('table_id');
	}

	public function get_output($output, $data)
	{
		$this->config->extension_url = 'json';
		return json_encode(array('search' => array_values(array_unique($this->_words)), 'content' => $this->template->parse($output, $data)));
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
}

/*
NeoFrag Alpha 0.1
./neofrag/libraries/table.php
*/