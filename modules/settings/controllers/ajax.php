<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Settings\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function humans()
	{
		return $this->config->nf_humans_txt;
	}

	public function robots()
	{
		return $this->config->nf_robots_txt;
	}

	public function debug_bar()
	{
		if ($tab = post('tab'))
		{
			$this->session->set('debug', 'tab', $tab);
		}
		else if ($tab === '')
		{
			$this->session->destroy('debug', 'tab');
		}
		else if ($height = (int)post('height'))
		{
			$this->session->set('debug', 'height', $height);
		}
	}

	public function languages()
	{
		if (post())
		{
			foreach ($this->config->langs as $language)
			{
				if (($name = $language->info()->name) == post('language'))
				{
					if ($this->user->id)
					{
						$this->user->set('language', $language->__addon)->update();
					}
					else
					{
						$this->session->set('language', $language->info()->name);
					}

					if ($name != $this->config->lang->info()->name)
					{
						$this->url->redirect($this->url->base.$name.substr(post('url'), strlen($this->url->base.$this->config->lang->info()->name)));
					}

					break;
				}
			}
		}
		else
		{
			return $this->js('languages')
						->modal('Choisir ma langue', 'fas fa-globe')
						->body($this->view('languages'))
						->cancel();
		}
	}
}
