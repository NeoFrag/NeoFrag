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

class Url extends Core
{
	private $_external = FALSE;
	private $_const    = [];

	public function __construct()
	{
		parent::__construct();

		$this->_const['host']              = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
		$this->_const['base']              = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		$this->_const['request']           = preg_replace('#^'.preg_quote($this->_const['base'], '#').'#', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?: 'index';
		$this->_const['extension']         = extension($this->_const['request']);
		$this->_const['extension_allowed'] = $this->_const['extension'] == '';
		$this->_const['segments']          = explode('/', $this->_const['extension'] ? substr($this->_const['request'], 0, - strlen($this->_const['extension']) - 1) : $this->_const['request']);

		if (preg_match('/^(humans|robots)\.txt$/', $this->_const['request'], $match))
		{
			$this->_const['segments']      = explode('/', 'ajax/settings/'.$match[1]);
		}

		$this->_const['admin']             = $this->_const['segments'][0] == 'admin';
		$this->_const['ajax']              = isset($this->_const['segments'][(int)$this->_const['admin']]) && $this->_const['segments'][(int)$this->_const['admin']] == 'ajax';
		$this->_const['ajax_header']       = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		$this->_const['ajax_allowed']      = FALSE;
	}

	public function __get($name)
	{
		if (isset($this->_const[$name]))
		{
			if ($name == 'base' && $this->_external)
			{
				return $this->_const['host'].$this->_const['base'];
			}

			return $this->_const[$name];
		}

		return parent::__get($name);
	}

	public function __isset($name)
	{
		return isset($this->_const[$name]) ?: parent::__isset($name);
	}

	public function ajax()
	{
		return 	$this->ajax ||
				($this->ajax_header && $this->ajax_allowed) ||
				($this->extension_allowed && $this->extension != '');
	}

	public function external($external)
	{
		$this->_external = $external;
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/core/url.php
*/