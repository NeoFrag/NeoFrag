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

class Network extends Library
{
	protected $_auth;
	protected $_header = [];
	protected $_method;
	protected $_ssl_check;
	protected $_timeout;
	protected $_type;
	protected $_url;

	public function __construct($config = [])
	{
		$this->_ssl_check = $config['ssl_check'];
		$this->_timeout   = $config['timeout'];
	}

	public function __invoke($url)
	{
		$this->_url = $url;
		return $this->reset();
	}

	public function auth($user, $password)
	{
		$this->_auth = $user.':'.$password;
		return $this;
	}

	public function header($header)
	{
		$this->_header = array_merge($this->_header, (array)$header);
		return $this;
	}

	public function type($type)
	{
		$this->_type = $type;
		return $this;
	}

	public function method($method)
	{
		$this->_method = $method;
		return $this;
	}

	public function get($data = [])
	{
		return $this->_execute([
			'return' => TRUE,
			'url'    => $data ? $this->_url.'?'.http_build_query($data) : NULL
		]);
	}

	public function post($data = [])
	{
		return $this->_execute([
			'return'   => TRUE,
			'callback' => function($ch) use ($data){
				if ($data)
				{
					foreach ($this->_header as $header)
					{
						if (preg_match('#Content-Type: application/json#i', $header))
						{
							$data = json_encode($data);
							break;
						}
					}

					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
			}
		]);
	}

	public function stream($file, $callback = NULL)
	{
		return $this->_execute([
			'callback' => function($ch) use ($file, $callback){
				$f = fopen($file, 'w+b');

				curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use ($f, $callback){
					$bytes = fwrite($f, $data);

					if (is_callable($callback))
					{
						static $size = 0;
						static $total;

						if ($total === NULL)
						{
							$total = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
						}

						$callback($size += $bytes, $total);
					}

					return $bytes;
				});

				curl_exec($ch);

				fclose($f);
			}
		]);
	}

	protected function _execute($args = [])
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, !empty($args['url']) ? $args['url'] : $this->_url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $this->_timeout);

		if ($this->_auth)
		{
			curl_setopt($ch, CURLOPT_USERPWD, $this->_auth);
		}

		if ($this->_header)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_header);
		}

		if ($this->_method)
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_method);
		}

		if (!$this->_ssl_check)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}

		if (isset($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']))
		{
			curl_setopt($ch, CURLOPT_REFERER, (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		}

		$result = NULL;

		if (!empty($args['callback']))
		{
			$args['callback']($ch);
		}

		if (!empty($args['return']))
		{
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$result = curl_exec($ch);

			if (curl_getinfo($ch, CURLINFO_HTTP_CODE) >= 400)
			{
				$result = FALSE;
			}
			else if (($this->_type === NULL && in_string('application/json', curl_getinfo($ch, CURLINFO_CONTENT_TYPE))) || $this->_type == 'json')
			{
				$result = json_decode(utf8_string($result));
			}
		}

		curl_close($ch);

		return $result;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/network.php
*/