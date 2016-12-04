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

class m_search_c_index extends Controller_Module
{
	public function index($module_name = '', $page = '')
	{
		$this->title($this('search'));

		$count  = 0;
		$row    = [];
		$search = '';

		if (!empty($_GET['q']) && ($search = rawurldecode($_GET['q'])))
		{
			$keywords = $not_keywords = [];
			$results  = [];

			foreach (array_map($trim = create_function('$a', 'return trim($a, \';,."\\\'\');'), preg_split('/[\s;,.]*(-?"[^"]+")[\s;,.]*|[\s;,.]*(-?\'[^\']+\')[\s;,.]*|[\s;,.]+/', $search, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE)) as $keyword)
			{
				if (substr($keyword, 0, 1) == '-')
				{
					$not_keywords[] = call_user_func($trim, substr($keyword, 1));
				}
				else
				{
					$keywords[] = call_user_func($trim, $keyword);
				}
			}

			$keywords     = array_filter($keywords);
			$not_keywords = array_filter($not_keywords);

			if ($keywords)
			{
				$queries = [
					[$not_keywords, 'NOT LIKE', 'AND'],
					[$keywords,     'LIKE',     'OR']
				];
				
				foreach ($this->addons->get_modules() as $module)
				{
					if (($search_controller = $module->load->controller('search')) && ($columns = $search_controller->search()))
					{
						foreach ($queries as $query)
						{
							if ($query[0])
							{
								$args = [];
								
								foreach ($query[0] as $keyword)
								{
									foreach ($columns as $col)
									{
										array_push($args, $col.' '.$query[1],  '%'.addcslashes($keyword, '%_').'%', $query[2]);
									}
								}

								call_user_func_array([$this->db, 'where'], $args);
							}
						}

						if ($c = count($result = $this->db->get()))
						{
							$results[] = [$module, $search_controller, $result, $c];
							$count += $c;
						}
					}
				}
			}

			if ($count)
			{
				array_natsort($results, function($a){
					return $a[0]->get_title();
				});
				
				$panels = [];
				
				foreach ($results as $result)
				{
					$content = [];
					$details = FALSE;

					if (($name = url_title($result[0]->name)) == $module_name)
					{
						foreach ($this->pagination->fix_items_per_page(10)->get_data($result[2], $page) as $data)
						{
							$content[] = $result[1]->method('detail', [$data, $keywords]);
						}

						$details = TRUE;
					}
					else if (!$module_name)
					{
						foreach (array_slice($result[2], 0, 3) as $data)
						{
							$content[] = $result[1]->method('index', [$data, $keywords]);
						}
					}
					
					if ($content)
					{
						$panels[] = new Panel([
							'title'   => icon($result[0]->icon).' '.$result[0]->get_title(),
							'url'     => 'search/'.$result[0]->name.'.html?q='.rawurlencode($search),
							'content' => implode('<hr />', $content),
							'footer'  => (!$details && $result[3] > 3) ? '<a href="'.url('search/'.$result[0]->name.'.html?q='.rawurlencode($search)).'" class="btn btn-default btn-sm">'.$this('see_all_results').'</a>' : ''
						]);
					}
					
					if ($details && $pagination = $this->pagination->get_pagination())
					{
						$panels[] = new Panel([
							'content' => $pagination,
							'body'    => FALSE,
							'style'   => 'panel-back'
						]);
					}
				}
				
				if (!$panels)
				{
					redirect('search.html?q='.rawurlencode($search));
				}

				$row[] = new Row(
					new Col(
						new Panel([
							'body'    => FALSE,
							'content' => $this->load->view('results', [
								'keywords' => $search,
								'results'  => $results,
								'count'    => $count
							])
						]),
						'col-md-3'
					),
					new Col($panels, 'col-md-9')
				);
			}
		}

		return array_merge([
			new Row(
				new Col(
					new Panel([
						'title'   => $this('search'),
						'icon'    => 'fa-search',
						'content' => $this->load->view('index', [
							'results'  => (bool)$count,
							'keywords' => $search
						])
					])
				)
			)], $row);
	}
}

/*
NeoFrag Alpha 0.1.5.2
./neofrag/modules/search/controllers/index.php
*/