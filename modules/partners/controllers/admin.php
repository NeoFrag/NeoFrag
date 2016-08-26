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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_partners_c_admin extends Controller_Module
{
	public function index()
	{
		$this	->table
				->add_columns([
					[
						'content' => function($data){
							return button_sort($data['partner_id'], 'admin/ajax/partners/sort.html');
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
						'title'   => '<span data-toggle="tooltip" title="Visites">'.icon('fa-line-chart').'</span>',
						'content' => function($data){
							return $data['count'];
						}
					],
					[
						'content' => [
							function($data){
								return button_edit('admin/partners/'.$data['partner_id'].'/'.$data['name'].'.html');
							},
							function($data){
								return button_delete('admin/partners/delete/'.$data['partner_id'].'/'.$data['name'].'.html');
							}
						],
						'size'    => TRUE
					]
				])
				->data($this->model()->get_partners())
				->no_data('Aucun partenaire');

		return new Panel([
			'title'   => 'Liste des partenaires',
			'icon'    => 'fa-star-o',
			'content' => $this->table->display(),
			'footer'  => button_add('admin/partners/add.html', 'Ajouter un partenaire')
		]);
	}

	public function add()
	{
		$this	->subtitle('Ajouter un partenaire')
				->form
				->add_rules('partners')
				->add_submit($this('add'))
				->add_back('admin/partners.html');

		if ($this->form->is_valid($post))
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

			redirect('admin/partners.html');
		}

		return new Panel([
			'title'   => 'Ajouter un partenaire',
			'icon'    => 'fa-star-o',
			'content' => $this->form->display()
		]);
	}

	public function _edit($partner_id, $name, $logo_light, $logo_dark, $website, $facebook, $twitter, $count, $code, $title, $description)
	{
		$this	->subtitle($title)
				->form
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
				->add_submit($this('edit'))
				->add_back('admin/partners.html');

		if ($this->form->is_valid($post))
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

			redirect_back('admin/partners.html');
		}

		return new Panel([
			'title'   => 'Éditer le partenaire',
			'icon'    => 'fa-star-o',
			'content' => $this->form->display()
		]);
	}

	public function delete($partner_id, $title)
	{
		$this	->title('Supprimer le partenaire')
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le partenaire <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model()->delete_partner($partner_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/partners/controllers/admin.php
*/