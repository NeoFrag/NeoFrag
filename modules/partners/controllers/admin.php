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
						'title'   => 'Nom',
						'content' => function($data){
							return $data['title'];
						}
					],
					[
						'title'   => 'Site internet',
						'content' => function($data){
							return '<a href="'.$data['website'].'" target="_blank">'.$data['website'].'</a>';
						}
					],
					[
						'title'   => '<span data-toggle="tooltip" title="Visites">'.icon('fas fa-chart-line').'</span>',
						'content' => function($data){
							return $data['count'];
						}
					],
					[
						'content' => [
							function($data){
								return $this->is_authorized('modify_partners') ? $this->button_update('admin/partners/'.$data['partner_id'].'/'.$data['name']) : NULL;
							},
							function($data){
								return $this->is_authorized('delete_partners') ? $this->button_delete('admin/partners/delete/'.$data['partner_id'].'/'.$data['name']) : NULL;
							}
						],
						'size'    => TRUE
					]
				])
				->data($this->model()->get_partners())
				->no_data('Aucun partenaire');

		return $this->panel()
					->heading('Liste des partenaires', 'fas fa-bars')
					->body($this->table()->display())
					->footer_if($this->is_authorized('add_partners'), $this->button_create('admin/partners/add', 'Ajouter un partenaire'));
	}

	public function add()
	{
		$this	->subtitle('Ajouter un partenaire')
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

			notify('Partenaire ajouté avec succès');

			redirect('admin/partners');
		}

		return $this->panel()
					->heading('Ajouter un partenaire', 'far fa-handshake')
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

			notify('Partenaire modifié avec succès');

			redirect_back('admin/partners');
		}

		return $this->panel()
					->heading('Éditer le partenaire', 'far fa-handshake')
					->body($this->form()->display());
	}

	public function delete($partner_id, $title)
	{
		$this	->title('Supprimer le partenaire')
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), 'Êtes-vous sûr(e) de vouloir supprimer le partenaire <b>'.$title.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->model()->delete_partner($partner_id);

			return 'OK';
		}

		return $this->form()->display();
	}
}
