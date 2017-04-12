<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Date extends Library
{
	protected $_datetime;

	public function __invoke($datetime = NULL)
	{
		if ($datetime)
		{
			$datetime = date_create_from_format('U',           $datetime) ?:
						date_create_from_format('Y-m-d H:i:s', $datetime) ?:
						date_create_from_format('Y-m-d',       $datetime) ?:
						date_create_from_format('H:i:s',       $datetime) ?:
						date_create($datetime);
		}

		$this->_datetime = $datetime ?: date_create_from_format('U.u', number_format(microtime(TRUE), 6, '.', ''))->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		return $this;
	}

	public function __toString()
	{
		return '<time datetime="'.$this->format('Y-m-d\TH:i:s').'">'.time_span($this->_datetime->getTimestamp()).'</time>';
	}

	public function __debugInfo()
	{
		return [
			'datetime' => $this->_datetime,
			'html'     => $this->__toString(),
			'sql'      => $this->sql()
		];
	}

	public function __sleep()
	{
		return ['_datetime'];
	}

	public function modify($modify)
	{
		$this->_datetime->modify($modify);
		return $this;
	}

	public function sub($interval)
	{
		if (!is_a($interval, '\DateInterval'))
		{
			$interval = date_interval_create_from_date_string($interval);
		}

		$this->_datetime->sub($interval);
		return $this;
	}

	public function diff($date)
	{
		if (!is_a($date, 'NF\NeoFrag\Libraries\Date'))
		{
			$date = $this->date($date);
		}

		if (($diff = date_create('@0')->add($date->_datetime->diff($this->_datetime))->getTimestamp()) < 0)
		{
			return -1;
		}
		else if ($diff > 0)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function sql()
	{
		return $this->format('Y-m-d H:i:s');
	}

	public function interval($date)
	{
		if (!is_a($date, 'NF\NeoFrag\Libraries\Date'))
		{
			$date = $this->date($date);
		}

		return $this->_datetime->diff($date->_datetime);
	}

	public function timestamp()
	{
		return $this->_datetime->getTimestamp();
	}

	public function format($format)
	{
		return $this->_datetime->format($format);
	}

	public function locale($format)
	{
		return timetostr($format, $this->timestamp());
	}

	public function short_date()
	{
		return $this->locale($this->config->lang->date()['short_date']);
	}

	public function long_date()
	{
		return $this->locale($this->config->lang->date()['long_date']);
	}
}
