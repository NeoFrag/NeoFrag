<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Recruits\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index($recruits)
	{
		$panels = $this->array;

		foreach ($recruits as $recruit)
		{
			if (($recruit['closed'] || ($recruit['candidacies_accepted'] >= $recruit['size']) || ($recruit['date_end'] && strtotime($recruit['date_end']) < time())) && !$this->config->recruits_hide_unavailable)
			{
				$panels->append($this	->panel()
										->heading($recruit['title'], $recruit['icon'] ?: 'fas fa-bullhorn')
										->body('Cette offre n\'est plus disponible actuellement.')
										->color('info'));
			}
			else
			{
				if ($candidacy = $this->model()->postulated($this->user->id, $recruit['recruit_id'], $recruit['title']))
				{
					$footer = '<a href="'.url('recruits/candidacy/'.$candidacy['candidacy_id'].'/'.url_title($recruit['title'])).'" class="btn btn-primary">'.icon('fas fa-briefcase').' Voir ma candidature</a>';
				}
				else
				{
					$footer = '<a href="'.url('recruits/'.$recruit['recruit_id'].'/'.url_title($recruit['title'])).'" class="btn btn-light">'.icon('far fa-eye').' En savoir plus</a> <a href="'.url('recruits/postulate/'.$recruit['recruit_id'].'/'.url_title($recruit['title'])).'" class="btn btn-primary">'.icon('fas fa-briefcase').' Postuler</a>';
				}

				$panels->append($this	->panel()
										->heading($candidacy ? $recruit['title'].'<div class="float-right"><span class="badge badge-dark">J\'ai postulé !</span></div>' : $recruit['title'], $recruit['icon'] ?: 'fas fa-bullhorn', 'recruits/'.$recruit['recruit_id'].'/'.url_title($recruit['title']))
										->body($this->view('index', [
											'recruit_id'   => $recruit['recruit_id'],
											'title'        => $recruit['title'],
											'image_id'     => $recruit['image_id'],
											'date'         => $recruit['date'],
											'team_id'      => $recruit['team_id'],
											'team_name'    => $recruit['team_name'],
											'role'         => $recruit['role'],
											'size'         => $recruit['size'] - $recruit['candidacies_accepted'],
											'date_end'     => $recruit['date_end'],
											'introduction' => bbcode($recruit['introduction'])
										]))
										->footer_if($footer, $footer, 'right'));
			}
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading('Recrutement', 'fas fa-bullhorn')
									->body('<div class="text-center">Aucune offre n\'a été publiée pour le moment</div>')
									->color('info'));
		}
		else
		{
			$panels->append($this->module->pagination->panel());
		}

		return $panels;
	}

	public function _recruit($recruit_id, $title, $introduction, $description, $requierments, $date, $user_id, $size, $role, $icon, $date_end, $closed, $team_id, $image_id, $username, $avatar, $sex, $candidacies, $candidacies_pending, $candidacies_accepted, $candidacies_declined, $team_name)
	{
		$this->title($title);

		if (($this->access('recruits', 'recruit_postulate', $recruit_id)) && (!$date_end || strtotime($date_end) > time()))
		{
			if ($candidacy = $this->model()->postulated($this->user->id, $recruit_id, $title))
			{
				$href                  = '<a href="'.url('recruits/candidacy/'.$candidacy['candidacy_id'].'/'.url_title($candidacy['title'])).'" class="btn btn-success">'.icon('far fa-eye').' Voir ma candidature</a>';
				$recruit['postulated'] = TRUE;
			}
			else
			{
				if ($this->access('recruits', 'recruit_postulate', $recruit_id))
				{
					$href = '<a href="'.url('recruits/postulate/'.$recruit_id.'/'.url_title($title)).'" class="btn btn-primary btn-block">'.icon('fas fa-briefcase').' Postuler</a>';
				}
				else
				{
					$href = NULL;
				}

				$recruit['postulated'] = FALSE;
			}

			$postulate_panel = $this->panel()
									->heading($recruit['postulated'] ? 'J\'ai postulé' : 'Postuler', $recruit['postulated'] ? 'fas fa-check' : 'fab fa-black-tie')
									->body($recruit['postulated'] ? $this->view('recruit-postulate', [
														'postulated' => $recruit['postulated'],
														'status'     => $candidacy['status']
													]) : 'Vous n\'avez pas encore déposé de candidature pour cette offre de recrutement.')
									->footer($href);
		}
		else
		{
			$postulate_panel = $this->panel()
									->heading('Postuler', 'fab fa-black-tie')
									->body('Vous n\'êtes pas autorisé à déposer de candidature pour cette offre...')
									->color('info');
		}

		return $this->array
					->append(
						$this->row(
							$this->col(
								$this	->panel()
										->heading($title, ($icon ? $icon : 'fas fa-bullhorn'))
										->body($this->view('recruit', [
											'recruit_id'   => $recruit_id,
											'title'        => $title,
											'introduction' => bbcode($introduction),
											'description'  => bbcode($description),
											'requierments' => bbcode($requierments),
											'date'         => $date,
											'user_id'      => $user_id,
											'size'         => $size - $candidacies_accepted,
											'role'         => $role,
											'icon'         => $icon,
											'date_end'     => $date_end,
											'closed'       => $closed,
											'team_id'      => $team_id,
											'image_id'     => $image_id
										]))
							)
						)
					)
					->append(
						$this->row(
							$this	->col(
										$this	->panel()
												->heading('Informations', 'fas fa-info')
												->body($this->view('recruit-infos', [
																			'role'      => $role,
																			'size'      => $size,
																			'date_end'  => $date_end,
																			'team_id'   => $team_id,
																			'team_name' => $team_name
																		]))
									)
									->size('col-6'),
							$this	->col($postulate_panel)
									->size('col-6')
						)
					);
	}

	public function _postulate($recruit_id, $title, $introduction, $description, $requierments, $date, $recruit_user_id, $size, $role, $icon, $date_end, $closed, $team_id, $image_id, $username, $avatar, $sex, $candidacies, $candidacies_pending, $candidacies_accepted, $candidacies_declined, $team_name)
	{
		if ($candidacy = $this->model()->postulated($this->user->id, $recruit_id, $title))
		{
			return $this->panel()
						->heading('Déposer ma candidature', 'fab fa-black-tie')
						->body('Vous avez déjà déposé votre candidature pour cette offre le <b>'.timetostr('%e %b %Y', $candidacy['date']).'</b> !')
						->footer('<a href="'.url('recruits/candidacy/'.$candidacy['candidacy_id'].'/'.url_title($candidacy['title'])).'" class="btn btn-primary">'.icon('far fa-eye').' Voir ma candidature</a>')
						->color('info');
		}
		else
		{
			if ($candidacies_accepted < $size && $closed == FALSE && (!$date_end || strtotime($date_end) > time()))
			{
				$this	->form()
						->add_rules($rules = [
							'pseudo' => [
								'label' => 'Votre pseudo',
								'value' => $this->user->username,
								'type'  => 'text',
								'rules' => 'required'
							],
							'email' => [
								'label' => 'Adresse email',
								'value' => $this->user->email,
								'type'  => 'email',
								'rules' => 'required'
							],
							'date_of_birth' => [
								'label' => 'Date de naissance',
								'value' => $this->user->date_of_birth,
								'type'  => 'date',
								'check' => function($value){
									if ($value && strtotime($value) > strtotime(date('Y-m-d')))
									{
										return 'Vraiment ?! 2.1 Gigowatt !';
									}
								},
								'rules' => 'required'
							],
							'presentation' => [
								'label' => 'Présentez-vous',
								'type'  => 'editor'
							],
							'motivations' => [
								'label' => 'Vos motivations',
								'type'  => 'editor'
							],
							'experiences' => [
								'label' => 'Expériences',
								'type'  => 'editor'
							]
						])
						->add_captcha()
						->add_submit('Envoyer ma candidature');

				if ($this->form()->is_valid($post))
				{
					$candidacy_id = $this->model()->send_candidacy(	$recruit_id,
																	$this->user->id,
																	$this->user->username ?: $post['pseudo'],
																	$this->user->email    ?: $post['email'],
																	$post['date_of_birth'],
																	$post['presentation'],
																	$post['motivations'],
																	$post['experiences']);

					if ($this->config->recruits_alert && $this->user->id)
					{
						$users =  $this->db	->select('*')
											->from('nf_user')
											->where('deleted', FALSE)
											->get();

						$recipients = [];
						foreach ($users as $user)
						{
							if ($this->access('recruits', 'candidacy_vote', 0, NULL, $user['id']) || $this->access('recruits', 'candidacy_reply', 0, NULL, $user['id']))
							{
								$recipients[] = $user;
							}
						}

						if ($recipients)
						{
							$message_id = $this->db	->ignore_foreign_keys()
													->insert('nf_users_messages', [
														'title' => 'Nouvelle candidature :: '.$title
													]);

							$reply_id = $this->db	->insert('nf_users_messages_replies', [
														'message_id' => $message_id,
														'user_id'    => $this->user->id,
														'message'    => '<div class="alert alert-info m-0"><b>Message automatique.</b><br />Une nouvelle candidature vient d\'être déposée par '.($this->user->id ? $user['username'] : $post['pseudo']).'.<br /><br />Pour la visualiser, <a href="'.url('admin/recruits/candidacy/'.$candidacy_id.'/'.url_title($title)).'">cliquer ici</a>.</div>'
													]);

							$this->db	->where('message_id', $message_id)
										->update('nf_users_messages', [
											'reply_id'      => $reply_id,
											'last_reply_id' => $reply_id
										]);

							foreach ($recipients as $recipient)
							{
								$this->db->insert('nf_users_messages_recipients', [
									'user_id'    => $recipient['id'],
									'message_id' => $message_id,
									'date'       => NULL
								]);
							}
						}
					}

					notify('Candidature envoyée avec succès');

					if ($this->user())
					{
						redirect('recruits/candidacy/'.$candidacy_id.'/'.url_title($title));
					}
					else
					{
						redirect('recruits');
					}
				}

				return $this->panel()
							->heading('Déposer ma candidature', 'fab fa-black-tie')
							->body($this->view('postulate', [
													'recruit_id'   => $recruit_id,
													'title'        => $title,
													'role'         => $role,
													'icon'         => $icon,
													'date_end'     => $date_end,
													'form'         => $this->form()->display()
												]));
			}
			else
			{
				return $this->panel()
							->heading('Déposer ma candidature', 'fab fa-black-tie')
							->body('Oops... L\'offre <b>'.$title.'</b> n\'est plus disponible...')
							->color('danger');
			}
		}
	}

	public function _candidacy($candidacy_id, $recruit_id, $date, $user_id, $pseudo, $email, $date_of_birth, $presentation, $motivations, $experiences, $status, $reply_text, $title, $icon, $role, $team_id, $team_name, $username, $avatar, $sex)
	{
		return $this->array
					->append($this	->panel()
									->heading('Statut de ma candidature', 'fas fa-reply')
									->body($this->view('candidacy-status', [
										'status'     => $status,
										'reply_text' => bbcode($reply_text)
									]))
					)
					->append($this	->panel()
									->heading('Ma candidature', 'fab fa-black-tie')
									->body($this->view('candidacy', [
										'candidacy_id'  => $candidacy_id,
										'recruit_id'    => $recruit_id,
										'date'          => $date,
										'user_id'       => $user_id,
										'pseudo'        => $pseudo,
										'email'         => $email,
										'role'          => $role,
										'date_of_birth' => $date_of_birth,
										'presentation'  => bbcode($presentation),
										'motivations'   => bbcode($motivations),
										'experiences'   => bbcode($experiences),
										'reply'         => bbcode($reply_text),
										'title'         => $title,
										'icon'          => $icon,
										'username'      => $username,
										'avatar'        => $avatar,
										'sex'           => $sex,
										'team_id'       => $team_id,
										'team_name'     => $team_name
									]))
					)
					->append($this->panel_back());
	}
}
