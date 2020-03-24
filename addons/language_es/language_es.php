<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Language_Es;

use NF\NeoFrag\Addons\Language;

class Language_Es extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'Español',
			'icon'    => '🇪🇸',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.2'
			]
		];
	}

	public function locale()
	{
		return [
			'es_ES.UTF8',
			'es.UTF8',
			'es_ES.UTF-8',
			'es.UTF-8',
			'Spanish_Spain.1252'
		];
	}

	public function date()
	{
		return [
			'short_date'      => '%d/%m/%Y',
			'long_date'       => '%e de %B de %Y',
			'short_time'      => '%k:%M',
			'long_time'       => '%k:%M:%S',
			'short_date_time' => '%d/%m/%Y %k:%M',
			'long_date_time'  => '%A, %e de %B de %Y %k:%M'
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
