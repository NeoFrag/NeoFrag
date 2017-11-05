<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class View extends Library
{
	protected $_name;
	protected $_data;

	public function __invoke($name, $data = [])
	{
		$this->_name = $name;
		$this->_data = $data;

		return $this->reset();
	}

	public function content($content, $data = [])
	{
		if (in_string('<?php', $content))
		{
			$content = eval('ob_start(); ?>'.$content.'<?php return ob_get_clean();');
		}

		return $content;
	}

	public function __toString()
	{
		foreach ($paths = $this->load->paths('views') as $dir)
		{
			if (check_file($path = $dir.'/'.$this->_name.'.tpl.php'))
			{
				$data = array_merge($this->_data, $this->load->data);

				if ($this->debug->is_enabled())
				{
					$this->load->views[] = [$path, $this->_name.'.tpl.php', $data];
				}

				return $this->content(file_get_contents($path), $data);
			}
		}

		trigger_error('Unfound view: '.$this->_name.' in paths ['.implode(', ', array_filter($paths)).']', E_USER_WARNING);

		return '';
	}
}
