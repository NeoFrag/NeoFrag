<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

abstract class Authenticator extends NeoFrag
{
	protected $_enabled;
	protected $_settings;
	protected $_keys = ['id', 'secret'];

	public $name;
	public $title;
	public $color;
	public $icon;
	public $help;

	abstract public function data(&$params = []);

	public function __construct($name, $enabled, $settings = [])
	{
		$this->load      = NeoFrag();
		$this->name      = $name;
		$this->_enabled  = $enabled;
		$this->_settings = $settings;
	}

	public function is_setup()
	{
		foreach ($this->_keys as $key)
		{
			if (empty($this->_settings[$key]))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	public function is_enabled()
	{
		return $this->_enabled;
	}

	public function admin()
	{
		$settings = [];

		foreach ($this->_keys as $key)
		{
			$settings[$key] = !empty($this->_settings[$key]) ? utf8_htmlentities($this->_settings[$key]) : '';
		}

		return [
			'title'    => icon($this->icon).' '.$this->title,
			'help'     => icon('fa-info-circle').' <a href="'.$this->help.'" target="_blank">'.$this->help.'</a>',
			'settings' => $settings,
			'params'   => $this->_params()
		];
	}

	public function update($settings)
	{
		$this->_settings = [];

		foreach ($this->_keys as $key)
		{
			$this->_settings[$key] = !empty($settings[$key]) ? $settings[$key] : '';
		}

		$this->db	->where('name', $this->name)
					->update('nf_settings_authenticators', [
						'settings'   => serialize($this->_settings),
						'is_enabled' => $this->is_setup()
					]);
	}

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret']
		];
	}

	public function __toString()
	{
		$button = $this	->button()
						->tooltip($this->title)
						->icon($this->icon)
						->style('background-color', $this->color)
						->url('user/auth/'.url_title($this->name));

		return '<div class="btn-auth">'.$button.'</div>';
	}

	protected function _params()
	{
		return [
			'callback' => $this->url->host.url('user/auth/'.url_title($this->name))
		];
	}
}
