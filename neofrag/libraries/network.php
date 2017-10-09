<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Network extends Library
{
	protected $_auth;
	protected $_header = [];
	protected $_error;
	protected $_method;
	protected $_ssl_check = TRUE;
	protected $_timeout   = 1;
	protected $_type;
	protected $_url;

	public function __construct($caller, $config = [])
	{
		parent::__construct($caller);

		foreach (['ssl_check', 'timeout'] as $var)
		{
			if (array_key_exists($var, $config))
			{
				$this->{'_'.$var} = $config[$var];
			}
		}
	}

	public function __invoke($url)
	{
		$this->_url = $url;
		return $this;
	}

	public function auth($user, $password = '')
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

	public function error($error)
	{
		$this->_error = $error;
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
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

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

		curl_setopt($ch, CURLOPT_REFERER, $this->url->location);

		$result = NULL;

		if (!empty($args['callback']))
		{
			$args['callback']($ch);
		}

		if (!empty($args['return']))
		{
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$result = curl_exec($ch);

			if (($this->_type === NULL && in_string('application/json', curl_getinfo($ch, CURLINFO_CONTENT_TYPE))) || $this->_type == 'json')
			{
				$result = json_decode(utf8_string($result));
			}

			if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) >= 400)
			{
				if (is_a($this->_error, 'closure'))
				{
					call_user_func_array($this->_error, [$result, $code]);
				}

				$result = FALSE;
			}
		}

		curl_close($ch);

		return $result;
	}
}
