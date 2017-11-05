<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Output extends Core
{
	public $data = [];

	public function __toString()
	{
		$this->data = $this->load->data;

		$this->data['page_title'] = $this->config->nf_name.' :: '.$this->config->nf_description;
		$this->data['lang']       = $this->config->lang;
		$this->data['output']     = preg_replace('/\xEF\xBB\xBF/', '', ob_get_clean());

		if ($this->url->ajax())
		{
			$output = $this->load->module;
		}
		else
		{
			$this->data['module_actions'] = $this->load->module->get_actions();

			$this->data = array_merge($this->data, $this->load->module->load->data);

			$this->parse_data($this->data);

			if (!empty($this->data['module_title']) && $this->url->segments[0] != 'index')
			{
				$this->data['page_title'] = $this->data['module_title'].' :: '.$this->config->nf_name;
			}

			if (!empty($this->load->module->icon) || !empty($this->data['module_icon']))
			{
				$this->data['module_title'] = icon(!empty($this->data['module_icon']) ? $this->data['module_icon'] : $this->load->module->icon).' '.$this->data['module_title'];
			}

			notifications();
			
			if ($this->load->module->name == 'live_editor')
			{
				$this->data['body'] = $this->load->module;
			}
			else
			{
				$this->data['body'] = '';

				if (NeoFrag::live_editor())
				{
					$this->data['body'] = '<div id="live_editor" data-module-title="'.utf8_htmlentities($this->url->segments[0] == 'index' ? $this->label($this->lang('home'), 'fa-map-marker') : $this->data['module_title']).'"></div>';

					$this->load	->css('font.open-sans.300.400.600.700.800')
								->css('neofrag.live-editor');
				}

				$this->data['body'] .= $this->load->theme->view('body', $this->data);

				if ($this->load->modals)
				{
					$this->data['body'] .= implode($this->load->modals);
				}

				if (!NeoFrag::live_editor())
				{
					$this->data['body'] .= $this->debug->display();
				}
			}

			if (!$this->url->ajax() && $this->user('admin') && $this->url->request != 'admin/monitoring' && $this->module('monitoring')->need_checking())
			{
				$this->js_load('$.post(\''.url('admin/ajax/monitoring.json').'\', {refresh: false});');
			}

			$this->data['css']     = output('css');
			$this->data['js']      = output('js');
			$this->data['js_load'] = output('js_load');

			$output = $this->load->theme->view('default', $this->data);
		}

		if ($this->url->extension == 'json')
		{
			header('Content-Type: application/json; charset=UTF-8');
		}
		else if ($this->url->extension == 'xml')
		{
			header('Content-Type: application/xml; charset=UTF-8');
			$output = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n".$output;
		}
		else if ($this->url->extension == 'txt')
		{
			header('Content-Type: text/plain; charset=UTF-8');
			$output = utf8_html_entity_decode($output);
		}
		else
		{
			header('Content-Type: text/html; charset=UTF-8');
		}

		return (string)$output;
	}

	public function zone($zone_id)
	{
		static $dispositions;
		
		if ($dispositions === NULL)
		{
			$this->db	->select('zone', 'disposition_id', 'disposition', 'page')
						->from('nf_dispositions')
						->where('theme', $this->load->theme->name)
						->order_by('page DESC');

			$pages = ['page', '*', 'OR'];

			if ($this->url->segments[0] == 'index')
			{
				$pages[] = 'page';
				$pages[] = '/';
				$pages[] = 'OR';
			}
			else
			{
				for ($i = count($segments = $this->router->segments); $i > 0; $i--)
				{
					$pages[] = 'page';
					$pages[] = implode('/', array_slice($segments, 0, $i)).'/*';
					$pages[] = 'OR';
				}
			}

			call_user_func_array([$this->db, 'where'], $pages);
			
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
			return parent::zone($disposition['disposition_id'], unserialize($disposition['disposition']), $disposition['page'], $zone_id);
		}

		return '';
	}

	public function parse($content, $data = [])
	{
		if (is_a($content, 'closure'))
		{
			$content = call_user_func($content, $data);
		}

		return $content;
	}

	public function parse_data(&$data)
	{
		array_walk_recursive($data, function(&$a) use (&$data){
			$a = $this->parse($a, $data);
		});
	}
}
