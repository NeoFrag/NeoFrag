<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Settings\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$this	->subtitle($this->lang('Préférences générales'))
				->icon('fa-cog');

		$modules = $pages = [];

		foreach (NeoFrag()->model2('addon')->get('module') as $module)
		{
			if ($module->is_administrable())
			{
				$modules[] = $module;
			}
		}

		array_natsort($modules, function($a){
			return $a->info()->title;
		});

		foreach ($modules as $module)
		{
			$pages[$module->name] = $module->info()->title;

			if ($module->name == 'pages')
			{
				foreach ($module->model()->get_pages() as $page)
				{
					if ($page['published'])
					{
						$pages['pages/'.$page['name']] = str_repeat('&nbsp;', 10).$page['title'];
					}
				}
			}
		}

		$this	->form()
				->add_rules([
					'name' => [
						'label'  => $this->lang('Titre du site'),
						'value'  => $this->config->nf_name,
						'rules'  => 'required'
					],
					'description' => [
						'label'  => $this->lang('Description du site'),
						'value'  => $this->config->nf_description,
						'rules'  => 'required'
					],
					'contact' => [
						'label'  => $this->lang('Email de contact'),
						'value'  => $this->config->nf_contact,
						'type'   => 'email',
						'rules'  => 'required'
					],
					'default_page' => [
						'label'  => $this->lang('Page d\'accueil'),
						'values' => $pages,
						'value'  => $this->config->nf_default_page,
						'type'   => 'select',
						'rules'  => 'required'
					],
					'humans_txt' => [
						'label'  => '<a href="http://humanstxt.org/">humans.txt</a>',
						'type'   => 'textarea',
						'value'  => $this->config->nf_humans_txt
					],
					'robots_txt' => [
						'label'  => '<a href="http://www.robotstxt.org//">robots.txt</a>',
						'type'   => 'textarea',
						'value'  => $this->config->nf_robots_txt
					],
					'analytics' => [
						'label'  => $this->lang('Code analytics'),
						'type'   => 'textarea',
						'value'  => $this->config->nf_analytics
					]
				])
				->add_submit($this->lang('Valider'))
				->display_required(FALSE);

		if ($this->form()->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				if ($var == 'analytics')
				{
					$value = implode("\r\n", array_map('trim', explode("\r\n", trim(preg_replace('#&lt;script.*?&gt;(.*?)&lt;/script&gt;#is', '\1', $value)))));
				}

				$this->config('nf_'.$var, $value);
			}

			notify('Préférences générales sauvegardées avec succès');

			refresh();
		}

		return $this->_layout(function($col){
			$col->append($this	->panel()
								->heading($this->lang('Préférences générales'), 'fa-cog')
								->body($this->form()->display())
			);
		});
	}

	public function registration()
	{
		$this	->subtitle('Gestions des inscriptions')
				->icon('fa-sign-in fa-rotate-90');

		$users = $this->db	->select('id as user_id', 'username')
							->from('nf_user')
							->where('deleted', FALSE)
							->order_by('username')
							->get();

		$list_users = [];

		foreach ($users as $user)
		{
			$list_users[$user['user_id']] = $user['username'];
		}

		array_natsort($list_users);

		$this	->form()
				->add_rules([
					[
						'label'   => 'Inscription',
						'type'    => 'legend'
					],
					'registration_status' => [
						'label'   => 'Statut',
						'type'    => 'radio',
						'value'   => $this->config->nf_registration_status,
						'values'  => ['Ouvertes', 'Fermées']
					],
					/*'registration_validation' => [
						'label'   => 'Validation',
						'type'    => 'radio',
						'value'   => $this->config->nf_registration_validation,
						'values'  => ['Automatique', 'Confirmation par e-mail']
					],*/
					'registration_charte' => [
						'label'   => 'Règlement',
						'value'   => $this->config->nf_registration_charte,
						'type'    => 'editor'
					],
					[
						'label'   => 'Message de bienvenue',
						'type'    => 'legend'
					],
					'welcome' => [
						'type'    => 'checkbox',
						'checked' => ['on' => $this->config->nf_welcome],
						'values'  => ['on' => 'Envoyer un message privé aux nouveaux membres']
					],
					'welcome_user_id' => [
						'label'   => 'Auteur du message',
						'values'  => $list_users,
						'value'   => $this->config->nf_welcome_user_id,
						'type'    => 'select',
						'size'    => 'col-5'
					],
					'welcome_title' => [
						'label'   => 'Titre du message',
						'value'   => $this->config->nf_welcome_title,
						'type'    => 'text'
					],
					'welcome_content' => [
						'label'   => 'Message de bienvenue',
						'value'   => $this->config->nf_welcome_content,
						'type'    => 'editor'
					]
				])
				->add_submit($this->lang('Valider'))
				->display_required(FALSE);

		if ($this->form()->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				if ($var == 'welcome')
				{
					$value = in_array('on', $value);
				}

				$this->config('nf_'.$var, $value);
			}

			notify('Gestion des inscriptions sauvegardée avec succès');

			refresh();
		}

		return $this->_layout(function($col){
			$col->append($this	->panel()
								->heading('Gestions des inscriptions', 'fa-sign-in fa-rotate-90')
								->body($this->form()->display())
			);
		});
	}

	public function team()
	{
		$this	->subtitle('Notre structure')
				->icon('fa-users');

		$this	->form()
				->add_rules([
					'team_name' => [
						'label'       => 'Nom de l\'équipe',
						'value'       => $this->config->nf_team_name,
						'type'        => 'text'
					],
					'team_logo' => [
						'label'       => 'Logo',
						'value'       => $this->config->nf_team_logo,
						'type'        => 'file',
						'upload'      => 'logos',
						'info'        => ' d\'image (max. '.(file_upload_max_size() / 1024 / 1024).' Mo)',
						'check'       => function($filename, $ext){
							if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
							{
								return 'Veuiller choisir un fichier d\'image';
							}
						},
						'description' => 'Le logo pourra être affiché dans le widget type <b>header</b> <i>(en remplacement du titre et slogan)</i>.'
					],
					'team_type' => [
						'label'       => 'Type de structure',
						'value'       => $this->config->nf_team_type,
						'type'        => 'text',
						'size'        => 'col-4',
						'description' => '<b>Exemple:</b> Association, entreprise, marque, etc...'
					],
					'team_creation' => [
						'label'       => 'Date de création',
						'value'       => $this->config->nf_team_creation,
						'type'        => 'date',
						'size'        => 'col-4'
					],
					'team_biographie' => [
						'label'       => 'Biographie',
						'value'       => $this->config->nf_team_biographie,
						'type'        => 'textarea'
					]
				])
				->add_submit($this->lang('Valider'))
				->display_required(FALSE);

		if ($this->form()->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				$this->config('nf_'.$var, $value);
			}

			notify('Informations sauvegardées avec succès');

			refresh();
		}

		return $this->_layout(function($col){
			$col->append($this	->panel()
								->heading('Notre structure', 'fa-users')
								->body($this->form()->display())
			);
		});
	}

	public function socials()
	{
		$this	->subtitle('Réseaux sociaux')
				->icon('fa-globe');

		$this	->form()
				->add_rules([
					'social_facebook' => [
						'label' => 'Facebook',
						'icon'  => 'fa-facebook',
						'value' => $this->config->nf_social_facebook,
						'type'  => 'url'
					],
					'social_twitter' => [
						'label' => 'Twitter',
						'icon'  => 'fa-twitter',
						'value' => $this->config->nf_social_twitter,
						'type'  => 'url'
					],
					'social_google' => [
						'label' => 'Google+',
						'icon'  => 'fa-google-plus',
						'value' => $this->config->nf_social_google,
						'type'  => 'url'
					],
					'social_steam' => [
						'label' => 'Page Steam',
						'icon'  => 'fa-steam',
						'value' => $this->config->nf_social_steam,
						'type'  => 'url'
					],
					'social_twitch' => [
						'label' => 'Twitch',
						'icon'  => 'fa-twitch',
						'value' => $this->config->nf_social_twitch,
						'type'  => 'url'
					],
					'social_dribble' => [
						'label' => 'Dribbble',
						'icon'  => 'fa-dribbble',
						'value' => $this->config->nf_social_dribble,
						'type'  => 'url'
					],
					'social_behance' => [
						'label' => 'Behance',
						'icon'  => 'fa-behance',
						'value' => $this->config->nf_social_behance,
						'type'  => 'url'
					],
					'social_deviantart' => [
						'label' => 'DeviantArt',
						'icon'  => 'fa-deviantart',
						'value' => $this->config->nf_social_deviantart,
						'type'  => 'url'
					],
					'social_flickr' => [
						'label' => 'Flickr',
						'icon'  => 'fa-flickr',
						'value' => $this->config->nf_social_flickr,
						'type'  => 'url'
					],
					'social_github' => [
						'label' => 'Github',
						'icon'  => 'fa-github',
						'value' => $this->config->nf_social_github,
						'type'  => 'url'
					],
					'social_instagram' => [
						'label' => 'Instagram',
						'icon'  => 'fa-instagram',
						'value' => $this->config->nf_social_instagram,
						'type'  => 'url'
					],
					'social_youtube' => [
						'label' => 'Youtube',
						'icon'  => 'fa-youtube',
						'value' => $this->config->nf_social_youtube,
						'type'  => 'url'
					]
				])
				->add_submit($this->lang('Valider'))
				->display_required(FALSE);

		if ($this->form()->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				$this->config('nf_'.$var, $value);
			}

			notify('Réseaux sociaux sauvegardés avec succès');

			refresh();
		}

		return $this->_layout(function($col){
			$col->append($this	->panel()
								->heading('Réseaux sociaux', 'fa-globe')
								->body($this->form()->display())
			);
		});
	}

	public function captcha()
	{
		$this	->subtitle('Sécurité anti-bots')
				->icon('fa-shield');

		$this	->form()
				->add_rules([
					'captcha_public_key' => [
						'label' => 'Clé publique Google',
						'value' => $this->config->nf_captcha_public_key,
						'type'  => 'text'
					],
					'captcha_private_key' => [
						'label' => 'Clé privée Google',
						'value' => $this->config->nf_captcha_private_key,
						'type'  => 'text'
					]
				])
				->add_submit($this->lang('Valider'))
				->display_required(FALSE);

		if ($this->form()->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				$this->config('nf_'.$var, $value);
			}

			notify('Configuration de Google reCAPTCHA sauvegardée avec succès');

			refresh();
		}

		return $this->_layout(function($col){
			$col->append($this	->panel()
								->heading('Configuration de Google reCAPTCHA', 'fa-shield')
								->body('<div class="alert alert-info"><a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">https://www.google.com/recaptcha/intro/index.html</a></div>'.$this->form()->display())
			);
		});
	}

	public function email()
	{
		$this	->subtitle('Serveur e-mail')
				->icon('fa-envelope-o');

		$this	->form()
				->add_rules([
					'email_smtp' => [
						'label'  => 'Serveur SMTP',
						'value'  => $this->config->nf_email_smtp,
						'type'   => 'text'
					],
					'email_username' => [
						'label'  => 'Utilisateur',
						'value'  => $this->config->nf_email_username,
						'type'   => 'text',
						'size'   => 'col-5'
					],
					'email_password' => [
						'label'  => 'Mot de passe',
						'value'  => $this->config->nf_email_password,
						'type'   => 'password',
						'size'   => 'col-5'
					],
					'email_secure' => [
						'label'  => 'Sécurité',
						'type'   => 'radio',
						'value'  => $this->config->nf_email_secure,
						'values' => ['SSL', 'TLS']
					],
					'email_port' => [
						'label'  => 'Port',
						'value'  => $this->config->nf_email_port,
						'type'   => 'number',
						'size'   => 'col-2'
					]
				])
				->add_submit($this->lang('Valider'))
				->display_required(FALSE);

		if ($this->form()->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				$this->config('nf_'.$var, $value);
			}

			notify('Configuration du serveur SMTP sauvegardée avec succès');

			refresh();
		}

		return $this->_layout(function($col){
			$col->append($this	->panel()
								->heading('Serveur e-mail', 'fa-envelope-o')
								->body($this->form()->display())
			);
		});
	}

	public function maintenance()
	{
		$this	->subtitle($this->lang('Maintenance'))
				->icon('fa-power-off')
				->css('maintenance')
				->js('maintenance');

		$form_opening = $this->form()
			->add_rules([
				'opening' => [
					'type'  => 'datetime',
					'value' => $this->config->nf_maintenance_opening
				]
			])
			->fast_mode()
			->add_submit($this->lang('Valider'))
			->save();

		$form_maintenance = $this->form()
			->add_rules([
				'title' => [
					'label' => $this->lang('Titre'),
					'type'  => 'text',
					'value' => $this->config->nf_maintenance_title
				],
				'content' => [
					'label' => $this->lang('Contenu'),
					'type'  => 'editor',
					'value' => $this->config->nf_maintenance_content
				],
				'logo' => [
					'label'  => $this->lang('Logo'),
					'value'  => $this->config->nf_maintenance_logo,
					'type'   => 'file',
					'upload' => 'maintenance',
					'info'   => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
					'check'  => function($filename, $ext){
						if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
						{
							return $this->lang('Veuiller choisir un fichier d\'image');
						}
					}
				],
				'background' => [
					'label'  => $this->lang('Image de fond'),
					'value'  => $this->config->nf_maintenance_background,
					'type'   => 'file',
					'upload' => 'maintenance',
					'info'   => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
					'check'  => function($filename, $ext){
						if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
						{
							return $this->lang('Veuiller choisir un fichier d\'image');
						}
					}
				],
				'repeat' => [
					'label'  => $this->lang('Répéter l\'image'),
					'value'  => $this->config->nf_maintenance_background_repeat,
					'values' => [
						'no-repeat' => $this->lang('Non'),
						'repeat-x'  => $this->lang('Horizontalement'),
						'repeat-y'  => $this->lang('Verticalement'),
						'repeat'    => $this->lang('Les deux')
					],
					'type'   => 'radio'
				],
				'positionX' => [
					'label'  => $this->lang('Position'),
					'value'  => $this->config->nf_maintenance_background_position ? explode(' ', $this->config->nf_maintenance_background_position)[0] : '',
					'values' => [
						'left'   => $this->lang('Gauche'),
						'center' => $this->lang('Centré'),
						'right'  => $this->lang('Droite')
					],
					'type'   => 'radio'
				],
				'positionY' => [
					'value'  => $this->config->nf_maintenance_background_position ? explode(' ', $this->config->nf_maintenance_background_position)[1] : '',
					'values' => [
						'top'    => $this->lang('Haut'),
						'center' => $this->lang('Milieu'),
						'bottom' => $this->lang('Bas')
					],
					'type'   => 'radio'
				],
				'background_color' => [
					'label' => $this->lang('Couleur de fond'),
					'value' => $this->config->nf_maintenance_background_color,
					'type'  => 'colorpicker'
				],
				'text_color' => [
					'label' => $this->lang('Couleur du texte'),
					'value' => $this->config->nf_maintenance_text_color,
					'type'  => 'colorpicker'
				],
				'facebook' => [
					'label' => 'Facebook',
					'icon'  => 'fa-facebook',
					'value' => $this->config->nf_maintenance_facebook,
					'type'  => 'url'
				],
				'twitter' => [
					'label' => 'Twitter',
					'icon'  => 'fa-twitter',
					'value' => $this->config->nf_maintenance_twitter,
					'type'  => 'url'
				],
				'google' => [
					'label' => 'Google+',
					'icon'  => 'fa-google-plus',
					'value' => $this->config->{'nf_maintenance_google-plus'},
					'type'  => 'url'
				],
				'steam' => [
					'label' => 'Steam',
					'icon'  => 'fa-steam',
					'value' => $this->config->nf_maintenance_steam,
					'type'  => 'url'
				],
				'twitch' => [
					'label' => 'Twitch',
					'icon'  => 'fa-twitch',
					'value' => $this->config->nf_maintenance_twitch,
					'type'  => 'url'
				]
			])
			->add_submit($this->lang('Valider'))
			->save();

		if ($form_opening->is_valid($post))
		{
			$this->config('nf_maintenance_opening', $post['opening']);
			refresh();
		}
		else if ($form_maintenance->is_valid($post))
		{
			$this	->config('nf_maintenance_title',               $post['title'])
					->config('nf_maintenance_content',             $post['content'])
					->config('nf_maintenance_logo',                $post['logo'], 'int')
					->config('nf_maintenance_background',          $post['background'], 'int')
					->config('nf_maintenance_background_repeat',   $post['repeat'])
					->config('nf_maintenance_background_position', $post['positionX'].' '.$post['positionY'])
					->config('nf_maintenance_background_color',    $post['background_color'])
					->config('nf_maintenance_text_color',          $post['text_color'])
					->config('nf_maintenance_facebook',            $post['facebook'])
					->config('nf_maintenance_twitter',             $post['twitter'])
					->config('nf_maintenance_google-plus',         $post['google'])
					->config('nf_maintenance_steam',               $post['steam'])
					->config('nf_maintenance_twitch',              $post['twitch'])
					->config('nf_version_css',                     time());

			refresh();
		}

		return $this->_layout(function($right, $left) use ($form_maintenance, $form_opening){
			$right->append($this	->panel()
								->heading($this->lang('Personnalisation de la page de maintenance'), 'fa-paint-brush')
								->body($form_maintenance->display())
			);

			$left	->append($this	->panel()
								->heading($this->lang('Statut du site'), 'fa-power-off')
								->body($this->view('maintenance'))
					)
					->append($this	->panel()
									->heading($this->lang('Ouverture programmée'), 'fa-clock-o')
									->body($form_opening->display())
					);
		});
	}

	public function copyright()
	{
		return $this->subtitle('Copyright')
					->icon('fa-copyright')
					->_layout(function($col){
						$col->append($this	->form2()
											->info($this->html()
														->attr('class', 'alert alert-primary')
														->content('	<h5 class="alert-heading">Mots magiques</h5>
																	<dl>
																		<dt>Lien vers NeoFrag</dt>
																			<dd>{neofrag}</dd>
																		<dt>Nom du site</dt>
																			<dd>{name}</dd>
																		<dt>Symbole '.icon('fa-copyright').'</dt>
																			<dd>{copyright}</dd>
																		<dt>Année</dt>
																			<dd>{year}</dd>
																	</dl>')
											)
											->rule('copyright', 'Copyright', $this->config->nf_copyright)
											->success(function($data){
												$this->config('nf_copyright', $data['copyright']);
												notify('Copyright modifié');
												refresh();
											})
											->panel()
											->title('Copyright')
						);
					});
	}

	protected function _layout($callback)
	{
		$menu = $this->widget('navigation')->output('vertical', [
			'links' => [
				[
					'title' => 'Préférences générales',
					'icon'  => 'fa-cog',
					'url'   => 'admin/settings'
				],
				[
					'title' => 'Maintenance',
					'icon'  => 'fa-power-off',
					'url'   => 'admin/settings/maintenance'
				],
				[
					'title' => 'Gestions des inscriptions',
					'icon'  => 'fa-sign-in fa-rotate-90',
					'url'   => 'admin/settings/registration'
				],
				[
					'title' => 'Notre structure',
					'icon'  => 'fa-users',
					'url'   => 'admin/settings/team'
				],
				[
					'title' => 'Réseaux sociaux',
					'icon'  => 'fa-globe',
					'url'   => 'admin/settings/socials'
				],
				[
					'title' => 'Sécurité anti-bots',
					'icon'  => 'fa-shield',
					'url'   => 'admin/settings/captcha'
				],
				/*[
					'title' => 'Serveur e-mail',
					'icon'  => 'fa-envelope-o',
					'url'   => 'admin/settings/email'
				],*/
				[
					'title' => 'Copyright',
					'icon'  => 'fa-copyright',
					'url'   => 'admin/settings/copyright'
				]
			]
		]);

		$row = $this->row(
			$left  = $this->col($menu)->size('col-3'),
			$right = $this->col()->size('col-9')
		);

		$callback($right, $left);

		return $row;
	}
}
