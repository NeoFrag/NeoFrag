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
		$this->extension('txt');

		if ($this->url->request != 'humans.txt' || !$this->config->nf_humans_txt)
		{
			$this->error();
		}

		return $this->config->nf_humans_txt;
	}

	public function robots()
	{
		$this->extension('txt');

		if ($this->url->request != 'robots.txt' || !$this->config->nf_robots_txt)
		{
			$this->error();
		}

		return $this->config->nf_robots_txt;
	}

	public function debug()
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
					$lang = \NeoFrag()->model2('addon')->get('language', $name, FALSE);

					if ($this->user->id)
					{
						$this->user->set('language', $lang->id)->update();
					}
					else
					{
						$this->session('language', $lang->id);
					}

					if ($name != $this->config->lang)
					{
						return $this->json([
							'redirect' => $this->url->base.$name.substr(post('url'), strlen($this->url->base.$this->config->lang))
						]);
					}

					break;
				}
			}
		}
		else
		{
			return $this->js('languages')
						->modal('Choisir ma langue', 'fa-globe')
						->body($this->view('languages'))
						->cancel();
		}
	}
}
