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

class Pagination extends Library
{
	private $_data;
	private $_items_per_page = 10;
	private $_page           = 1;
	private $_overload       = TRUE;
	private $_fixed          = FALSE;

	public function get_data($data = [], $page = '')
	{
		if ($page === '' && empty($data) && !empty($this->_data))
		{
			return $this->_data;
		}

		$this->_data     = $data;
		$this->_overload = FALSE;
		
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
			throw new Exception(NeoFrag::UNFOUND);
		}
		
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
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function count()
	{
		static $count;
		
		if ($count === NULL)
		{
			$count = count($this->_data);
		}
		
		return $count;
	}
	
	public function get_pagination($size = 'mini')
	{
		if (!$this->_data || $this->count() <= $this->_items_per_page || $this->_items_per_page == 0)
		{
			return '';
		}

		return $this->display($this->get_url(), $this->count(), $size, $this->_items_per_page, $this->_fixed, $this->_page);
	}
	
	public function display($base_url, $nb_pages, $size = 'sm', $items_per_page = 0, $fixed = TRUE, $current_page = 0)
	{
		if (!in_array($size, ['xs', 'sm', 'lg']))
		{
			$size = 'sm';
		}
		
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
				$buttons[] = '<span class="btn btn-default btn-'.$size.'">'.$p.'</span>';
			}
			else
			{
				$buttons[] = '<a class="btn btn-default btn-'.$size.(($current_page == $p) ? ' active' : '').'" href="'.$base_url.(($p > 1) ? '/page/'.$p.((!$fixed) ? '/'.$items_per_page : '') : '').'.html">'.$p.'</a>';
			}
		}

		return '<div class="pagination">'.implode(' ', $buttons).'</div>';
	}

	public function get_url()
	{
		return url(implode('/', ($pos = array_search('page', $this->config->segments_url)) !== FALSE || ($pos = array_search('all', $this->config->segments_url)) !== FALSE ? array_slice($this->config->segments_url, 0, $pos) : $this->config->segments_url));
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

/*
NeoFrag Alpha 0.1.4
./neofrag/libraries/pagination.php
*/