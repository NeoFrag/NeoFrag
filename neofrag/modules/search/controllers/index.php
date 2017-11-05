<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_search_c_index extends Controller_Module
{
	public function index($module_name = '', $page = '')
	{
		$this->title($this->lang('search'));

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
					if (($search_controller = $module->controller('search')) && ($columns = $search_controller->search()))
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
						$panels[] = $this	->panel()
											->heading($result[0]->get_title(), $result[0]->icon, 'search/'.$result[0]->name.'?q='.rawurlencode($search))
											->body(implode('<hr />', $content))
											->footer(!$details && $result[3] > 3 ? '<a href="'.url('search/'.$result[0]->name.'?q='.rawurlencode($search)).'" class="btn btn-default btn-sm">'.$this->lang('see_all_results').'</a>' : '');
					}
					
					if ($details && $pagination = $this->pagination->get_pagination())
					{
						$panels[] = $this	->panel()
											->body($pagination, FALSE);
					}
				}
				
				if (!$panels)
				{
					redirect('search?q='.rawurlencode($search));
				}

				$row[] = $this->row(
					$this	->col(
								$this	->panel()
										->body($this->view('results', [
											'keywords' => $search,
											'results'  => $results,
											'count'    => $count
										]), FALSE)
							)
							->size('col-md-3'),
					$this	->col($panels)
							->size('col-md-9')
				);
			}
		}

		return array_merge([
			$this->row(
				$this->col(
					$this	->panel()
							->heading($this->lang('search'), 'fa-search')
							->body($this->view('index', [
								'results'  => (bool)$count,
								'keywords' => $search
							]))
				)
			)], $row);
	}
}
