<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Language_Pt;

use NF\NeoFrag\Addons\Language;

class Language_Pt extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'Português',
			'icon'    => '🇵🇹',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.2'
			]
		];
	}

	public function locale()
	{
		return [
			'pt_PT.UTF8',
			'pt.UTF8',
			'pt_PT.UTF-8',
			'pt.UTF-8',
			'Portuguese_Portugal.1252'
		];
	}

	public function date()
	{
		return [
			'short_date'      => '%d/%m/%Y',
			'long_date'       => '%e de %B de %Y',
			'short_time'      => '%H:%M',
			'long_time'       => '%H:%M:%S',
			'short_date_time' => '%d/%m/%Y %H:%M',
			'long_date_time'  => '%A, %e de %B de %Y %H:%M'
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
