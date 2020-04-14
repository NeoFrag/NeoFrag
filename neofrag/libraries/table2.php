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
	protected $_no_data = '';
	protected $_columns = [];

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
		$table  = (string)$this;
		$footer = '';

		if ($has_search = $this->_has_search())
		{
			$this->ajax();
		}

		if (!empty($this->_collection->pagination) && ($pagination = $this->_collection->pagination->get_pagination()))
		{
			$footer = (string)$pagination;
		}

		$panel = parent	::panel()
						->heading()
						->style('panel-table')
						->data('id', $this->__id());

		if ($table)
		{
			$panel->body($table, FALSE);

			if ($has_search)
			{
				$panel->heading($this	->html('input')
										->attr('class', 'table-search')
										->attr('placeholder', 'Rechercher...')
										->attr('autocomplete', 'off'));
			}
		}
		else
		{
			$panel->body($this->_no_data ?: NeoFrag()->lang('Il n\'y a rien ici pour le moment'));
		}

		if ($footer)
		{
			$panel->footer($footer);
		}

		return $panel;
	}

	public function __toString()
	{
		array_walk($this->_columns, function($col){
			$col->collection($this->_collection, post('search'), '');
		});

		if ($data = $this->_collection ? $this->_collection->get() : $this->_data)
		{
			NeoFrag()	->css('table2')
						->js('table2');

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

			return $table->__toString();
		}

		return '';
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

	protected function _has_search()
	{
		foreach ($this->_columns as $col)
		{
			if ($col->has_search())
			{
				return TRUE;
			}
		}
	}
}
