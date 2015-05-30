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

class Router extends Core
{
	private $_consecutive_slashes = FALSE;
	private $_extension           = FALSE;
	private $_invalid_characters  = FALSE;

	public function __construct()
	{
		parent::__construct();
		
		$segments = $this->config->segments_url;
		
		if ($segments[0] == 'index')
		{
			$segments[0] = $this->config->nf_default_page;
		}
		
		if ($this->config->admin_url && $this->config->request_url != 'admin.html')
		{
			$segments = array_offset_left($segments);
		}
		
		if ($this->config->ajax_url)
		{
			$segments = array_offset_left($segments);
		}

		$this->load->theme($this->user('theme') ?: $this->config->nf_default_theme);

		$this->_check_invalid_characters();
		$this->_check_consecutive_slashes();
		$this->_check_extension();

		if ($this->_invalid_characters && $this->_consecutive_slashes)
		{
			if ($this->_extension)
			{
				if ($this->config->site != 'default' && $this->config->segments_url[0] != 'index')
				{
					array_unshift($segments, $this->config->nf_default_page);
				}
				
				$this->load->module(array_shift($segments), $segments);

				if ($this->config->admin_url)
				{
					$this->load->theme('admin');
				}

				return;
			}
		}

		if (!$this->_invalid_characters || !$this->_consecutive_slashes || !$this->_extension)
		{
			/* TODO
			 * Charger le module d'erreur pour proposer une URL correcte ou des éléments de recherche
			 * Mettre un HEADER HTTP de redirection vers la page corrigée (si elle existe et qu'elle est unique)
			 */

			 $this->load->module('error');
		}
	}

	private function _check_invalid_characters()
	{
		if (array_diff(array_map('rawurlencode', array_map('rawurldecode', $this->config->segments_url)), $this->config->segments_url))
		{
			$this->profiler->log('Caractères interdits dans l\'URL', Profiler::ERROR);
		}
		else
		{
			$this->_invalid_characters = TRUE;
		}
	}

	private function _check_consecutive_slashes()
	{
		if (strpos($this->config->request_url, '//') !== FALSE)
		{
			$this->profiler->log('Slashs concécutifs dans l\'URL', Profiler::ERROR);
		}
		else
		{
			$this->_consecutive_slashes = TRUE;
		}
	}

	private function _check_extension()
	{
		if (in_array($this->config->extension_url, array('html', 'json', 'xml', 'txt')) || $this->assets->is_asset())
		{
			$this->_extension = TRUE;
		}
		else
		{
			$this->profiler->log('Extension .html requise dans l\'URL', Profiler::ERROR);
		}
	}

	public function profiler()
	{
		$output = '	<a href="#" data-profiler="router"><i class="icon-chevron-'.(($this->session('profiler', 'router')) ? 'down' : 'up').' pull-right"></i></a>
					<h2>Router</h2>
					<div class="profiler-block">
						<table class="table table-striped">
							<tbody>
								<tr>
									<td style="width: 200px;"><b>Characters a-z A-Z 0-9 - / .</b></td>
									<td>'.($this->_invalid_characters ? '<i class="fa fa-check text-success" title="OK"></i>' : '<i class="fa fa-close text-danger" title="BAD"></i>').'</td>
								</tr>
								<tr>
									<td style="width: 200px;"><b>Consecutive slashes //</b></td>
									<td>'.($this->_consecutive_slashes ? '<i class="fa fa-check text-success" title="OK"></i>' : '<i class="fa fa-close text-danger" title="BAD"></i>').'</td>
								</tr>
								<tr>
									<td style="width: 200px;"><b>Extension .'.(($this->config->request_url == 'sitemap.xml') ? 'xml' : 'html').'</b></td>
									<td>'.($this->_extension ? '<i class="fa fa-check text-success" title="OK"></i>' : '<i class="fa fa-close text-danger" title="BAD"></i>').'</td>
								</tr>
								<tr>
									<td style="width: 200px;"><b>URL</b></td>
									<td>'.$this->config->request_url.'</td>
								</tr>
							</tbody>
						</table>
					</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/router.php
*/