<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Table2 extends Library
{
	protected $_collection;
	protected $_data     = [];
	protected $_no_data  = '';
	protected $_columns  = [];
	protected $_counters = [];
	protected $_filters  = '';

	public function __invoke($data)
	{
		$this->__id();

		$args = func_get_args();

		if (is_string($data))
		{
			if ($path = $this->__caller->__path('tables', $data.'.php', $paths))
			{
				include $path;
			}
			else
			{
				trigger_error('Unfound table: '.$data.' in paths ['.implode(';', $paths).']', E_USER_WARNING);
			}

			array_shift($args);
		}

		$data = array_shift($args);

		if (is_a($data, 'NF\NeoFrag\Libraries\Collection'))
		{
			$this->_collection = $data;
		}
		else if (is_a($data, 'NF\NeoFrag\Loadables\Model2'))
		{
			$this->_collection = $data->collection();
		}
		else if (is_a($data, 'NF\NeoFrag\Libraries\Array_'))
		{
			$this->_data = $data->values();
		}
		else if (method_exists($data, '__toArray'))
		{
			$data = $data->__toArray();
		}

		if (is_array($data))
		{
			$this->_data = array_values($data);
		}

		$this->_no_data = array_shift($args);

		return $this;
	}

	public function set_id($id)
	{
		$this->__id($id);
		return $this;
	}

	public function col()
	{
		$args    = func_get_args();
		$content = array_pop($args);

		if (!$args)
		{
			if (is_a($content, 'NF\NeoFrag\Libraries\Table_col'))
			{
				$this->_columns[] = $content;
			}
			else
			{
				$this->_columns[] = $this	->table_col()
											->content($content);
			}
		}
		else
		{
			$col = $this->table_col()
						->content($content);

			$vars = ['title', 'size', 'align'];

			while ($args && $vars)
			{
				$col->{array_shift($vars)}(array_shift($args));
			}

			$this->_columns[] = $col;
		}

		return $this;
	}

	public function counter($select, $label, $db = NULL)
	{
		$this->_counters[] = [$select, $label, $db];
		return $this;
	}

	public function compact($content)
	{
		return $this->col($this	->table_col()
								->content($content)
								->compact()
		);
	}

	public function action($action)
	{
		return $this->compact(function($model) use ($action){
			if ($action = $model->action($action))
			{
				return $action->__button();
			}
		});
	}

	public function update()
	{
		return $this->action('update');
	}

	public function delete()
	{
		return $this->action('delete');
	}

	public function panel()
	{
		$footers = [];

		if ($this->_counters)
		{
			$counters = [];

			$collection = $this->_collection->clone();

			foreach ($this->_counters as list($select,, $db))
			{
				if (is_a($db, 'closure'))
				{
					call_user_func_array($db, [$collection]);
				}

				$counters[] = $select;
			}

			$results = array_values($collection->select(...$counters)->aggregate()->order_by()->limit()->row(FALSE));

			$counters = [];

			foreach ($this->_counters as $i => list(, $label))
			{
				$counters[] = is_a($label, 'closure') ? $label($results[$i]) : $this->lang($label, $results[$i], print_number($results[$i]));
			}

			$footers[] = $this	->html()
								->align('right')
								->content(implode('&nbsp;&nbsp;&bull;&nbsp;&nbsp;', $counters));
		}

		$table = (string)$this;

		if (!empty($this->_collection->pagination) && ($pagination = $this->_collection->pagination->get_pagination()))
		{
			$footers[] = $pagination;
		}

		$panel = parent	::panel()
						->heading()
						->heading_if($this->_filters, function($filters){
							$output = $this	->button('Filtrer', 'fas fa-filter', 'light btn-sm')
											->align('right')
											->modal($this	->_filters
															->info('<small>Le caractère % permet des recherches partielles</small>')
															->submit('Filtrer')
															->modal('Filtrer', 'fas fa-filter')
															->close()
															->small()
											);

							if ($this->session->get('table2', 'filters', $this->_filters->__id()))
							{
								$output = $this	->html()
												->attr('class', 'btn-group')
												->align('right')
												->append($output)
												->append($this->_filters_reset());
							}

							return $output;
						})
						->style('panel-table')
						->data('id', $this->__id());

		if ($table)
		{
			if ($this->_data === NULL)
			{
				$panel->body($table);
			}
			else
			{
				$panel->body($table, FALSE);
			}
		}

		foreach ($footers as $footer)
		{
			$panel->footer($footer);
		}

		return $panel;
	}

	public function modal($title, $icon = '')
	{
		$modal = parent	::modal($title, $icon)
						->body($this, FALSE);

		return $modal;
	}

	public function __toString()
	{
		$ajax = FALSE;

		$sorts = $this->session('table2', 'sorts', $this->__id()) ?: [];

		if ($this->_collection)
		{
			$this->_filters = $this->_collection->filters();
		}

		if ($post = $this->input->post->get('table2'))
		{
			if ($post['id'] == $this->__id())
			{
				$ajax = TRUE;

				if (array_key_exists('sort', $post))
				{
					$sort = '';

					if (isset($sorts[$post['sort']]))
					{
						if ($sorts[$post['sort']] == 'asc')
						{
							$sort = 'desc';
						}
					}
					else
					{
						$sort = 'asc';
					}

					if (empty($post['action']))
					{
						$sorts = [];
					}

					if (!$sort || (!empty($post['action']) && $post['action'] == 'drop'))
					{
						unset($sorts[$post['sort']]);
					}
					else
					{
						$sorts[$post['sort']] = $sort;
					}

					$this->session->set('table2', 'sorts', $this->__id(), $sorts);
				}
			}
			else
			{
				return '';
			}
		}
		else if ($this->input->get->get('table_id') == $this->__id())
		{
			if ($this->input->get->get('action') == 'reset_filters')
			{
				if ($this->_filters)
				{
					$this->session->destroy('table2', 'filters', $this->_filters->__id());
				}

				redirect($this->url->query($this->input->get->clone()
															->destroy('table_id')
															->destroy('action')));
			}
		}

		$output = '';

		$cols = [];

		foreach (array_keys($sorts) as $i)
		{
			$cols[$i] = $this->_columns[$i];
		}

		$cols += array_diff_key($this->_columns, $sorts);

		if ($this->_filters)
		{
			$this->_filters->check();
		}

		$order_by = [];

		array_walk($cols, function($col, $i) use (&$order_by, $sorts){
			$order_by[] = $col->collection($this->_collection, isset($sorts[$i]) ? $sorts[$i] : NULL);
		});

		if ($order_by = array_filter($order_by))
		{
			$this->_collection->order_by(implode(', ', $order_by));
		}

		$data = $this->_collection ? $this->_collection->get() : $this->_data;

		if (!$ajax)
		{
			NeoFrag()->css('table2');
		}

		if ($data)
		{
			if (!$ajax)
			{
				NeoFrag()->js('table2');
			}

			$columns = $this->_columns;
			$table_id = spl_object_hash($this);

			foreach ($data as $i => $row)
			{
				foreach ($columns as $j => $col)
				{
					if ($col->execute($table_id, $i, $row) !== '')
					{
						unset($columns[$j]);
						continue;
					}
				}

				if (!$columns)
				{
					break;
				}
			}

			foreach (array_keys($columns) as $i)
			{
				unset($this->_columns[$i]);
			}

			$i = 0;

			$table = $this	->html('table')
							->attr('class', 'table table-hover table-striped')
							->content($this	->html('tbody')
											->content(array_map(function($row) use ($table_id, &$i){
												return $this->html('tr')
															->content(array_map(function($col) use ($table_id, $row, $i){
																return $col->display($table_id, $i, $row);
															}, $this->_columns))
															->exec(function() use (&$i){
																$i++;
															});
											}, $data)));

			if ($this->_has_header())
			{
				$i = 0;

				$table->prepend($this	->html('thead')
										->content($this	->html('tr')
														->content(array_map(function($col) use (&$i, $sorts){
															return $col->header($sorts, $i++);
														}, $this->_columns))));
			}

			$output .= $table->__toString();
		}
		else
		{
			$this->_data = NULL;
			$output .= $this->html()
							->attr('class', 'table-empty')
							->exec(function($html){
								if ($this->_filters && $this->session->get('table2', 'filters', $this->_filters->__id()))
								{
									$html->content(NeoFrag()->lang('Aucun résultat trouvé').$this->_filters_reset()->outline()->color('danger btn-sm'));
								}
								else
								{
									$html->content(NeoFrag()->lang($this->_no_data ?: 'Il n\'y a rien ici pour le moment'));
								}
							});
		}

		if ($ajax)
		{
			return $this->output->json([
				'content' => $output
			]);
		}

		return $output;
	}

	protected function _has_header()
	{
		foreach ($this->_columns as $col)
		{
			if ($col->has_header())
			{
				return TRUE;
			}
		}
	}

	protected function _filters_reset()
	{
		return $this->button()
					->tooltip('Retirer tous les filtres')
					->icon('fas fa-times')
					->color('light text-danger btn-sm')
					->url($this->url->query($this->input->get	->clone()
																->merge([
																	'table_id' => $this->__id(),
																	'action'   => 'reset_filters'
																])));
	}
}
