<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_partners extends Module
{
	public $title       = 'Partenaires';
	public $description = '';
	public $icon        = 'fa-star-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
		//Index
		'{id}/{url_title}'        => '_partner',

		//Admin
		'admin/{id}/{url_title*}' => '_edit'
	];

	public function settings()
	{
		$this	->form
				->add_rules([
					'partners_logo_display' => [
						'label'       => 'Logo',
						'value'       => $this->config->partners_logo_display,
						'values'      => [
							'logo_dark'  => 'Foncé',
							'logo_light' => 'Clair'
						],
						'type'        => 'radio',
						'description' => 'Utilisez les logos clairs s\'ils sont affichés sur un fond foncé',
						'size'        => 'col-md-4'
					]
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/addons#modules');

		if ($this->form->is_valid($post))
		{
			$this->config('partners_logo_display', $post['partners_logo_display']);

			redirect_back('admin/addons#modules');
		}

		return $this->panel()->body($this->form->display());
	}
}
