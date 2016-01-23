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
	public function index($search = '', $module_name = '', $page = '')
	{
		$this	->title('Rechercher');

		$this->load	->library('form')
					->add_rules(array(
						'keywords' => array(
							'type'          => 'text',
							'rules'			=> 'required'
						)
					));

		if ($keywords = post('keywords'))
		{
			redirect('search/'.rawurlencode($keywords).'.html');
		}
		else if ($search)
		{
			$value = $search;
		}
		else
		{
			$value = '';
		}

		if ($value)
		{
			$keywords = $not_keywords = array();
			$results  = array();
			$count    = 0;

			foreach (array_map($trim = create_function('$a', 'return trim($a, \';,."\\\'\');'), preg_split('/[\s;,.]*(-?"[^"]+")[\s;,.]*|[\s;,.]*(-?\'[^\']+\')[\s;,.]*|[\s;,.]+/', $value, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE)) as $keyword)
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
				foreach ($this->addons->get_modules() as $module)
				{
					if (($search_controller = $module->load->controller('search')) && ($result = $search_controller->search($keywords, $not_keywords)))
					{
						$results[$module->load->template->parse($search_controller->name, array(), $module->load)] = array($module, $search_controller, $result);
						$count += count($result);
					}
				}
			}

			ksort($results);

			if ($count == 0)
			{
				echo $this->load->view('unfound');
			}
			else
			{
				if ($count == 1)
				{
					$this->add_data('search_count', '1 résultat trouvé');
				}
				else
				{
					$this->add_data('search_count', $count.' résultats trouvés');
				}

				if (1 || count($results) > 1)
				{
					$modules = array();
					foreach ($results as $title => $result)
					{
						if (($name = url_title($result[0]->name)) == $module_name)
						{
							$display = $result[0]->load->template->parse($result[1]->method('detail', array($result[2])), array(), $result[0]->load);
							$details = TRUE;
						}
						else if (!$module_name)
						{
							$display = $result[0]->load->template->parse($result[1]->method('index', array($result[2])), array(), $result[0]->load);
						}
						else
						{
							$display = '';
						}

						$modules[] = array(
							'name'    => $name,
							'title'   => $title.' ('.count($result[2]).')',
							'display' => $display
						);
					}

					echo $this->load->view('results', array(
						'search'  => rawurlencode($value),
						'modules' => $modules,
						'details' => isset($details)
					));
				}
				else
				{

				}
			}
		}
		else
		{
			$value = '';
			echo $this->load->view('index');
		}

		$source = $this->db ->select('CONCAT("\"", keyword, "\"")')
							->from('nf_search_keywords')
							->order_by('count DESC')
							->get();

		$this->subtitle($this->load->view('search', array(
			'source' => utf8_htmlentities('['.implode(', ', $source).']'),
			'value'  => utf8_htmlentities($value)
		)));
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/search/controllers/index.php
*/