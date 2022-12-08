<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Language_Fr;

use NF\NeoFrag\Addons\Language;

class Language_Fr extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'Français',
			'icon'    => '🇫🇷',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.2'
			]
		];
	}

	public function locale()
	{
		return [
			'fr_FR.UTF8',
			'fr.UTF8',
			'fr_FR.UTF-8',
			'fr.UTF-8',
			'French_France.1252'
		];
	}

	public function date()
	{
		return [
			'short_date'      => 'd/m/Y',
			'long_date'       => 'j F Y',
			'short_time'      => 'H:i',
			'long_time'       => 'H:i:s',
			'short_date_time' => 'd/m/Y H:i',
			'long_date_time'  => 'l j F Y, H:i'
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
