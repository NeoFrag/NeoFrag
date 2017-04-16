<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Partners\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		$this->js('partners');

		$partners = $this->module('partners')->model()->get_partners();

		if (!empty($partners))
		{
			$total_partners = count($partners);

			return $this->panel()->body($this->view('index', [
				'partners'       => $partners,
				'total_partners' => $total_partners,
				'total_slides'   => ceil($total_partners / $settings['display_number']),
				'display_style'  => $settings['display_style'],
				'display_number' => $settings['display_number'],
				'display_height' => $settings['display_height'],
				'id'             => $settings['id']
			]), FALSE);
		}
	}

	public function column($settings = [])
	{
		$this	->css('partners')
				->js('partners');

		$partners = $this->module('partners')->model()->get_partners();

		if (!empty($partners))
		{
			return $this->panel()
						->heading('Partenaires')
						->body($this->view('column', [
							'partners'      => $partners,
							'display_style' => $settings['display_style']
						]));
		}
	}
}
