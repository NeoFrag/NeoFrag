<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($config = [])
	{
		if ($this->user())
		{
			$this->css('user');

			return $this->panel()
						->heading($this->lang('Espace membre'))
						->body($this->view('logged', [
							'username' => $this->user->username
						]), FALSE)
						->footer('<a href="'.url('user/logout').'">'.icon('fas fa-times').' '.$this->lang('Se déconnecter').'</a>');
		}
		else
		{
			if ($authenticators = NeoFrag()->model2('addon')->get('authenticator')->filter('is_enabled')->__toArray())
			{
				$this	->css('auth')
						->css('auth_mini');
			}

			return $this->module('user')
						->form2('login')
						->button_prepend_if($this->config->nf_registration_status, $this->button()
																						->title('Créer un compte')
																						->color('secondary')
																						->modal_ajax('ajax/user/register')
						)
						->button_prepend($this	->button()
												->title('Mot de passe oublié ?')
												->color('link')
												->modal_ajax('ajax/user/lost-password')
						)
						->panel()
						->title($this->lang('Espace membre').($authenticators ? '<div class="float-right">'.implode($authenticators).'</div>' : ''));
		}
	}

	public function index_mini($config = [])
	{
		return $this->view('index_mini', $config);
	}

	public function messages_inbox($config = [])
	{
		if ($this->user())
		{
			return $this->panel()
						->heading($this->lang('Messages privés'), 'fas fa-envelope')
						->body($this->view('messages_inbox', [
							'messages' => array_slice($this->module('user')->model('messages')->get_messages_inbox(), 0, 5)
						]), FALSE)
						->footer('<a class="btn btn-secondary" href="'.url('user/messages').'">'.icon('fas fa-inbox').' '.$this->lang('Boîte de réception').'</a> <a class="btn btn-primary" href="'.url('user/messages/compose').'">'.icon('fas fa-edit').' '.$this->lang('Rédiger').'</a>');
		}
	}
}
