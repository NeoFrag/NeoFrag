<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Captcha extends Labelable
{
	protected $_color;
	protected $_compact;
	protected $_session;

	public function __invoke($name = '')
	{
		if (!$this->config->nf_captcha_public_key || !$this->config->nf_captcha_private_key)
		{
			return;
		}

		$this->__id();

		$this->_check[] = function($post){
			if (!$this->user() && !($this->_session = $this->session('captcha', $this->__id())))
			{
				if (!empty($post[$this->_name]))
				{
					$result = $this	->network('https://www.google.com/recaptcha/api/siteverify')
									->get([
										'secret'   => $this->config->nf_captcha_private_key,
										'response' => $post[$this->_name],
										'remoteip' => $_SERVER['REMOTE_ADDR']
									]);

					if ($result === FALSE)
					{
						$this->_errors[] = 'Erreur serveur';
					}
					else if (!empty($result->success))
					{
						$this->_session = $this->session->set('captcha', $this->__id(), TRUE);
						return FALSE;
					}
				}

				$this->_errors[] = 'Veuiller valider ce CAPTCHA';
			}

			return FALSE;
		};

		$this->_template[] = function(&$input){
			if (!$this->user() && !$this->_session)
			{
				$this->js('captcha');

				$input = parent	::html()
								->attr('class', 'g-recaptcha')
								->attr_if($this->_color,   'data-theme', $this->_color)
								->attr_if($this->_compact, 'data-size', 'compact');
			}

			return FALSE;
		};

		return parent::__invoke('g-recaptcha-response');
	}

	public function dark()
	{
		$this->_color = 'dark';
		return $this;
	}

	public function compact()
	{
		$this->_compact = TRUE;
		return $this;
	}
}
