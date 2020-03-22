<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Crypt extends Library
{
	protected $_key = '';

	public function __construct($caller, $config)
	{
		parent::__construct($caller);

		$this->_key = $config['key'];
	}

	public function __invoke($data)
	{
		return rawurlencode(base64_encode(openssl_encrypt(json_encode($data), 'AES-128-CTR', $this->_key, 0, $this->_iv())));
	}

	public function decode($data)
	{
		return json_decode(openssl_decrypt(rawurldecode(base64_decode($data)), 'AES-128-CTR', $this->_key, 0, $this->_iv()));
	}

	public function hash($data, $length = 32, $charset = [])
	{
		if (!is_string($data))
		{
			$data = json_encode($data);
		}

		$data = hash('sha512', $data, TRUE);

		if (!$charset)
		{
			$charset = array_merge(range('a', 'z'), range(0, 9));
		}

		$n = count($charset);
		$i = 0;

		return implode(array_map(function($a) use ($charset, $n, &$i){
			return $charset[(++$i * array_sum(array_map('ord', str_split(sha1($a))))) % $n];
		}, str_split($data, ceil(strlen($data) / $length))));
	}

	protected function _iv()
	{
		if (!($iv = $this->session->get('crypt', 'token')))
		{
			$number = [rand(1, 9)];

			for ($i = 0; $i < 15; $i++)
			{
				$number[] = rand(0, 9);
			}

			$this->session->set('crypt', 'token', $iv = (int)implode($number));
		}

		return $iv;
	}
}
