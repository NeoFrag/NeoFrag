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

class Output extends Core
{
	public $data = array();

	public function __construct()
	{
		parent::__construct();

		if (method_exists($this->load->theme, 'load'))
		{
			$this->load->theme->load();
		}

		$this->data = $this->load->data;

		$this->data['base_url']   = $this->config->base_url;
		$this->data['page_title'] = $this->config->nf_name.' :: '.$this->config->nf_description;
		$this->data['lang']       = $this->config->lang;
		
		if (isset($this->load->module->table) && $this->load->module->table->is_ajax())
		{
			$output = $this->load->module->table->get_output(ob_get_clean(), $this->data);
		}
		else if ($this->config->ajax_url)
		{
			$output = $this->load->module->get_output();
		}
		else
		{
			if (NeoFrag::live_editor())
			{
				$this->load->css('neofrag.live-editor');
			}
			
			$this->data['module_actions'] = $this->load->module->get_actions();

			$this->data = array_merge($this->data, $this->load->module->load->data);

			$this->template->parse_data($this->data, $this->load->module->load);

			$this->data['module'] = ob_get_clean().$this->template->parse($this->load->module->get_output(), $this->data, $this->load->module->load);
			
			if ($this->config->admin_url)
			{
				$this->data['module'] = '<div class="module module-admin module-'.$this->load->module->get_name().'">'.$this->data['module'].$this->profiler->output().'</div>';
			}

			if ($this->config->segments_url[0] != 'index' && !empty($this->data['module_title']))
			{
				$this->data['page_title'] = $this->data['module_title'].' :: '.$this->config->nf_name;
			}

			if (!empty($this->load->module->icon) || !empty($this->data['module_icon']))
			{
				$this->data['module_title'] = $this->assets->icon(!empty($this->data['module_icon']) ? $this->data['module_icon'] : $this->load->module->icon).' '.$this->data['module_title'];
			}
			
			$output = $this->load->theme->load->view('default', $this->data);

			if (isset($this->load->css))
			{
				$this->data['css'] = array();
				
				foreach ($this->load->css as $css)
				{
					$this->data['css'][] = $this->template->parse('<link rel="stylesheet" href="{css '.$css[0].'.css}" type="text/css" media="'.$css[1].'" />', $this->data, $css[2])."\r\n";
				}
				
				$this->data['css'] = implode(array_unique($this->data['css']));
			}

			if (isset($this->load->js))
			{
				$this->data['js'] = array();

				foreach ($this->load->js as $js)
				{
					$this->data['js'][] = $this->template->parse('<script type="text/javascript" src="{js '.$js[0].'.js}"></script>', $this->data, $js[1])."\r\n";
				}
				
				$this->data['js'] = implode(array_unique($this->data['js']));
			}

			if (isset($this->load->js_load))
			{
				$this->data['js_load'] = '';

				foreach (array_unique($this->load->js_load) as $js)
				{
					$this->data['js_load'] .= $js;
				}
			}
		}

		if ($this->config->extension_url == 'json')
		{
			header('Content-Type: application/json; charset=UTF-8');
		}
		else if ($this->config->extension_url == 'xml')
		{
			header('Content-Type: application/xml; charset=UTF-8');

			$output = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n".$output;
		}
		else if ($this->config->extension_url == 'txt')
		{
			header('Content-Type: text/plain; charset=UTF-8');
			$output = utf8_html_entity_decode($output);
		}
		else
		{
			header('Content-Type: text/html; charset=UTF-8');
		}

		$output = $this->template->parse($output, $this->data);
		$output = $this->template->clean($output);

		/*
		if (extension_loaded('tidy'))
		{
			$output = tidy_repair_string($output, array(
				'break-before-br'  => TRUE,
				'char-encoding'    => 'utf8',
				'clean'            => FALSE,
				'indent'           => TRUE,
				'indent-spaces'    => 4,
				'output-xhtml'     => TRUE,
				'sort-attributes'  => 'alpha',
				'wrap'             => 0
			),
			'utf8');
		}*/

		echo $output;
	}

	public function display_zone($zone_id)
	{
		static $dispositions;
		
		if (is_null($dispositions))
		{
			$this->db	->select('zone', 'disposition_id', 'disposition', 'page')
						->from('nf_dispositions')
						->where('theme', $this->load->theme->get_name())
						->order_by('page DESC');

			$pages = array('page', '*', 'OR');

			if ($this->config->segments_url[0] == 'index')
			{
				$pages[] = 'page';
				$pages[] = '/';
				$pages[] = 'OR';
			}

			for ($i = count($segments = $this->load->module->segments); $i > 0; $i--)
			{
				$pages[] = 'page';
				$pages[] = implode('/', array_slice($segments, 0, $i)).'/*';
				$pages[] = 'OR';
			}

			call_user_func_array(array($this->db, 'where'), $pages);
			
			foreach ($this->db->get() as $disposition)
			{
				if (!isset($dispositions[$zone = $disposition['zone']]))
				{
					unset($disposition['zone']);
					$dispositions[$zone] = $disposition;
				}
			}
		}
		
		if (!empty($dispositions[$zone_id]))
		{
			$disposition = $dispositions[$zone_id];
			return Zone::display($disposition['disposition_id'], unserialize($disposition['disposition']), $disposition['page'], $zone_id);
		}

		return '';
	}

	public function profiler()
	{
		if (!$this->data)
		{
			return '';
		}

		ksort($this->data);

		$output = '	<a href="#" data-profiler="output"><i class="icon-chevron-'.(($this->session('profiler', 'output')) ? 'down' : 'up').' pull-right"></i></a>
					<h2>Output</h2>
					<div class="profiler-block">'.$this->profiler->table($this->data).'</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/output.php
*/