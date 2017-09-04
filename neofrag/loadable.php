<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

abstract class Loadable extends NeoFrag
{
	abstract public function paths();

	public $name;
	public $type;
	public $title;
	public $description;
	public $link;
	public $author;
	public $licence;
	public $version;
	public $nf_version;

	public function __construct($name, $type)
	{
		$this->name = $name;
		$this->type = $type;
	}

	public function __get($name)
	{
		return $name != 'load' ? parent::__get($name) : $this->load = load('loader', $this, $this->paths());
	}

	public function is_deactivatable()
	{
		return !empty(static::$core[$this->name]) || $this->is_removable();
	}

	public function is_removable()
	{
		return !isset(static::$core[$this->name]);
	}

	public function get_title($new_title = NULL)
	{
		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->lang($this->title, NULL);
		}

		return $title;
	}

	public function install()
	{
		$this->db->insert('nf_settings_addons', [
			'name'       => $this->name,
			'type'       => $this->type,
			'is_enabled' => TRUE
		]);

		return $this;
	}

	public function uninstall($remove = TRUE)
	{
		$this->db	->where('name', $this->name)
					->where('type', $this->type)
					->delete('nf_settings_addons');

		if ($remove)
		{
			dir_remove($this->type.'s/'.$this->name);
		}

		return $this;
	}

	public function reset()
	{
		$this->uninstall(FALSE);
		$this->config->reset();
		$this->install();

		return $this;
	}
}
