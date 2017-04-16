<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Language_En;

use NF\NeoFrag\Addons\Language;

class Language_En extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'English',
			'icon'    => '🇬🇧',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.1.7'
			]
		];
	}

	public function locale()
	{
		return [
			'en_GB.UTF8',
			'en_US.UTF8',
			'en.UTF8',
			'en_GB.UTF-8',
			'en_US.UTF-8',
			'en.UTF-8',
			'English_Australia.1252'
		];
	}

	public function date2sql(&$date)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $date, $match))
		{
			$date = $match[3].'-'.$match[2].'-'.$match[1];
		}
	}

	public function time2sql(&$time)
	{
		if (preg_match('#^(\d{2}):(\d{2})$#', $time, $match))
		{
			$time = $match[1].':'.$match[2].':00';
		}
	}

	public function datetime2sql(&$datetime)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})$#', $datetime, $match))
		{
			$datetime = $match[3].'-'.$match[2].'-'.$match[1].' '.$match[4].':'.$match[5].':00';
		}
	}
}
