<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Partners\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$this	->table()
				->add_columns([
					[
						'content' => function($data){
							return $this->button_sort($data['partner_id'], 'admin/ajax/partners/sort');
						},
						'size'    => TRUE
					],
					[
						'title'   => $this->lang('Nom'),
						'content' => function($data){
							return $data['title'];
						}
					],
					[
						'title'   => $this->lang('Site internet'),
						'content' => function($data){
							return '<a href="'.$data['website'].'" target="_blank">'.$data['website'].'</a>';
						}
					],
					[
						'title'   => '<span data-toggle="tooltip" title="'.$this->lang('Visites').'">'.icon('fa-line-chart').'</span>',
						'content' => function($data){
							return $data['count'];
						}
					],
					[
						'content' => [
							function($data){
								return $this->button_update('admin/partners/'.$data['partner_id'].'/'.$data['name']);
							},
							function($data){
								return $this->button_delete('admin/partners/delete/'.$data['partner_id'].'/'.$data['name']);
							}
						],
						'size'    => TRUE
					]
				])
				->data($this->model()->get_partners())
				->no_data($this->lang('Aucun partenaire'));

		return $this->panel()
					->heading($this->lang('Liste des partenaires'), 'fa-star-o')
					->body($this->table()->display())
					->footer($this->button_create('admin/partners/add', $this->lang('Ajouter un partenaire')));
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter un partenaire'))
				->form()
				->add_rules('partners')
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/partners');

		if ($this->form()->is_valid($post))
		{
			$this->model()->add_partner($post['title'],
										$post['logo_light'],
										$post['logo_dark'],
										$post['description'],
										$post['website'],
										$post['facebook'],
										$post['twitter'],
										$post['code']);

			notify($this->lang('Partenaire ajouté avec succès'));

			redirect('admin/partners');
		}

		return $this->panel()
					->heading($this->lang('Ajouter un partenaire'), 'fa-star-o')
					->body($this->form()->display());
	}

	public function _edit($partner_id, $name, $logo_light, $logo_dark, $website, $facebook, $twitter, $count, $code, $title, $description)
	{
		$this	->subtitle($title)
				->form()
				->add_rules('partners', [
					'title'       => $title,
					'logo_light'  => $logo_light,
					'logo_dark'   => $logo_dark,
					'description' => $description,
					'website'     => $website,
					'facebook'    => $facebook,
					'twitter'     => $twitter,
					'code'        => $code
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/partners');

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_partner(	$partner_id,
											$post['title'],
											$post['logo_light'],
											$post['logo_dark'],
											$post['description'],
											$post['website'],
											$post['facebook'],
											$post['twitter'],
											$post['code']);

			notify($this->lang('Partenaire modifié avec succès'));

			redirect_back('admin/partners');
		}

		return $this->panel()
					->heading($this->lang('Éditer le partenaire'), 'fa-star-o')
					->body($this->form()->display());
	}

	public function delete($partner_id, $title)
	{
		$this	->title($this->lang('Supprimer le partenaire'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le partenaire').' <b>'.$title.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->model()->delete_partner($partner_id);

			return $this->lang('OK');
		}

		return $this->form()->display();
	}
}
