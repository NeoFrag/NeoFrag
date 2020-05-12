<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Pagination extends Library
{
	private $_data;
	private $_items_per_page = 10;
	private $_page           = 1;
	private $_overload       = TRUE;
	private $_fixed          = FALSE;

	protected $_db;

	public function __construct($caller)
	{
		parent::__construct($caller);

		if (is_a($caller, 'NF\NeoFrag\Addons\Module'))//TODO
		{
			$caller->pagination = $this;
		}
	}

	public function __invoke($db, $page, $limit = NULL)
	{
		if ($limit !== NULL)
		{
			$this->fix_items_per_page($limit);
		}

		if ($page == 'all')
		{
			$this->_items_per_page = 0;
		}
		else if ($page && $this->_fixed && preg_match('#^page/([0-9]+?)$#', $page, $matches))
		{
			$this->_page = (int)$matches[1];
		}
		else if ($page && !$this->_fixed && preg_match('#^page/([0-9]+?)/(10|25|50|100)$#', $page, $matches))
		{
			$this->_page           = (int)$matches[1];
			$this->_items_per_page = (int)$matches[2];
		}

		if (!is_numeric($this->_page) || $this->_page == 0)
		{
			$this->error();
		}

		if ($db)
		{
			$this->_db = $db;
		}

		return $this;
	}

	public function limit()
	{
		if ($this->_items_per_page && $this->_db)
		{
			$db = clone $this->_db;
			$this->_db->limit(($this->_page - 1) * $this->_items_per_page, $this->_items_per_page);
			$this->_db = $db;
		}

		return $this;
	}

	public function get_data($data = [], $page = '')
	{
		if ($page === '' && empty($data) && !empty($this->_data))
		{
			return $this->_data;
		}

		$this->_data     = $data;
		$this->_overload = FALSE;

		$this(NULL, $page);

		if (empty($this->_data))
		{
			return [];
		}
		else if ($this->_items_per_page == 0)
		{
			return $this->_data;
		}
		else if ($page === '' || ($offset = ($this->_page - 1) * $this->_items_per_page) < $this->count())
		{
			return array_slice($this->_data, isset($offset) ? $offset : 0, $this->_items_per_page);
		}
		else
		{
			$this->error();
		}
	}

	public function count($total = NULL)
	{
		static $count;

		if ($total)
		{
			$count = $total;
		}
		else if ($count === NULL)
		{
			$count = $this->_data ? count($this->_data) : 0;
		}

		return $count;
	}

	public function get_pagination($size = 'mini')
	{
		if ($this->_db)
		{
			$this->count($total = $this->_db()->count());

			if ($total && ($this->_page - 1) * $this->_items_per_page >= $total)
			{
				redirect($this->get_url());
			}
		}

		if ($this->_items_per_page && $this->count() > $this->_items_per_page)
		{
			return $this->display(url($this->get_url()), $this->count(), $size, $this->_items_per_page, $this->_fixed, $this->_page);
		}

		return '';
	}

	public function panel()
	{
		if ($pagination = $this->get_pagination())
		{
			return parent	::panel()
							->style('card-transparent text-right')
							->body($pagination, FALSE);
		}
	}

	public function display($base_url, $nb_pages, $size = 'sm', $items_per_page = 0, $fixed = TRUE, $current_page = 0)
	{
		if ($items_per_page)
		{
			$nb_pages = ceil($nb_pages / $items_per_page);
		}

		$range = range(1, $nb_pages);

		if ($nb_pages >= 7)
		{
			if ($current_page >= 5 && $nb_pages - $current_page >= 5)
			{
				$range = array_merge([1, 2, '...'], range($current_page - 1, $current_page + 1), ['...'], array_offset_left($range, -2));
			}
			else if ($current_page > 1 && $current_page < 5)
			{
				$range = array_merge(range(1, $current_page + 1), ['...'], array_offset_left($range, -2));
			}
			else if ($current_page < $nb_pages && $nb_pages - $current_page < 5)
			{
				$range = array_merge(range(1, 2), ['...'], array_offset_left($range, $current_page - 2));
			}
			else
			{
				$range = array_merge([1, 2, '...'], array_offset_left($range, -2));
			}
		}

		$buttons = [];

		foreach ($range as $p)
		{
			if ($p == '...')
			{
				$buttons[] = '<span class="btn btn-light">'.$p.'</span>';
			}
			else
			{
				$buttons[] = '<a class="btn '.(($current_page == $p) ? 'btn-primary' : 'btn-light').'" href="'.$base_url.(($p > 1) ? '/page/'.$p.((!$fixed) ? '/'.$items_per_page : '') : '').(!empty($_GET) ? '?'.http_build_query($_GET, NULL, '&', PHP_QUERY_RFC3986) : '').'">'.$p.'</a>';
			}
		}

		return $this->html()
					->attr('class', 'pagination')
					->content($buttons);
	}

	public function get_url()
	{
		return preg_replace('_/?(page/\d+(/\d+)?|all)$_', '', $this->url->request);
	}

	public function get_items_per_page()
	{
		return $this->_items_per_page;
	}

	public function set_items_per_page($items_per_page)
	{
		if ($this->_overload && in_array($items_per_page, [0, 10, 25, 50, 100]))
		{
			$this->_items_per_page = $items_per_page;
		}

		return $this;
	}

	public function fix_items_per_page($items_per_page)
	{
		$this->_items_per_page = $items_per_page;

		$this->_fixed = TRUE;

		return $this;
	}

	public function get_page()
	{
		return $this->_page;
	}

	public function display_all()
	{
		$this->_items_per_page = 0;
		return $this->_data;
	}
}
