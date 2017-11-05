<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_awards extends Module
{
	public $title       = 'Palmarès';
	public $description = '';
	public $icon        = 'fa-trophy';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = '1.0';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $admin       = 'gaming';
	public $routes      = [
		//Index
		'{id}/{url_title}'             => '_award',
		'{url_title}/{id}/{url_title}' => '_filter',
		//Admin
		'admin{pages}'                 => 'index',
		'admin/{id}/{url_title*}'      => '_edit'
	];

	public function comments($award_id)
	{
		$award = $this->db	->select('name')
							->from('nf_awards')
							->where('award_id', $award_id)
							->row();

		if ($award)
		{
			return [
				'title' => $award,
				'url'   => 'awards/'.$award_id.'/'.url_title($award)
			];
		}
	}
}
