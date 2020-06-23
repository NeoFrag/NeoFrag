<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Date extends Library
{
	static protected $default_timezone;

	protected $_datetime;
	protected $_format;

	public function __invoke($datetime = NULL, $format = '', $timezone = NULL)
	{
		if (is_a($datetime, 'NF\NeoFrag\Libraries\Date'))
		{
			return $datetime;
		}

		$this->_timezone($timezone);

		if ($datetime === NULL)
		{
			$this->_format = $format;
		}
		else if (!is_a($datetime, '\DateTime'))
		{
			if ($format)
			{
				if ($datetime = date_create_from_format($format, $datetime, $timezone))
				{
					$this->_format = $format;
				}
			}
			else
			{
				$datetime = date_create_from_format(                 'U',           $datetime, $timezone) ?:
							date_create_from_format(                 'Y-m-d H:i:s', $datetime, $timezone) ?:
							date_create_from_format($this->_format = 'Y-m-d',       $datetime, $timezone) ?:
							date_create_from_format($this->_format = 'H:i:s',       $datetime, $timezone) ?:
							date_create_from_format($this->_format = 'H:i',         $datetime, $timezone) ?:
							($this->_format = '') ?:
							date_create($datetime, $timezone);
			}
		}

		$this->_datetime = $datetime ?: date_create_from_format('U.u', number_format(microtime(TRUE), 6, '.', ''), $timezone);

		$this->timezone();

		if ($this->_format == 'Y-m-d')
		{
			$this->_datetime->setTime(0, 0);
		}
		else if (preg_match('/^H:i/', $this->_format))
		{
			$this->_datetime->setDate(1, 1, 1);
		}

		return $this;
	}

	public function __toString()
	{
		$timestamp = $this->timestamp();
		$diff      = time() - $timestamp;
		$output    = '';

		if ($this->_format == 'Y-m-d')
		{
			$output = NeoFrag()->lang('Le %s', $this->short_date());

			if ($diff < 0)
			{
				if ($timestamp < strtotime('+2 days midnight'))
				{
					$output = NeoFrag()->lang('Demain');
				}
				else if ($timestamp < strtotime('+8 days midnight'))
				{
					$output = NeoFrag()->lang('%s prochain', ucfirst($this->locale('%A')));
				}
				else if ($timestamp < strtotime('+22 days midnight'))
				{
					$output = NeoFrag()->lang('Dans %d jours', floor($diff / 87840 * -1));
				}
			}
			else if ($diff > 0)
			{
				if ($timestamp >= strtotime('yesterday midnight'))
				{
					$output = NeoFrag()->lang('Hier');
				}
				else if ($timestamp >= strtotime('7 days ago midnight'))
				{
					$output = NeoFrag()->lang('%s dernier', ucfirst($this->locale('%A')));
				}
				else if ($timestamp >= strtotime('20 days ago midnight'))
				{
					$output = NeoFrag()->lang('Il y a %d jours', floor($diff / 87840));
				}
			}
			else
			{
				$output = NeoFrag()->lang('Aujourd\'hui');
			}
		}
		else
		{
			$output = NeoFrag()->lang('Le %s à %s', $this->short_date(), $this->short_time());

			if ($diff < 0)
			{
				if ($timestamp < strtotime('+1 days midnight'))
				{
					$output = NeoFrag()->lang('Aujourd\'hui à %s', $this->short_time());
				}
				else if ($timestamp < strtotime('+2 days midnight'))
				{
					$output = NeoFrag()->lang('Demain à %s', $this->short_time());
				}
				else if ($timestamp < strtotime('+8 days midnight'))
				{
					$output = NeoFrag()->lang('%s prochain à %s', ucfirst($this->locale('%A')), $this->short_time());
				}
				else if ($timestamp < strtotime('+22 days midnight'))
				{
					$output = NeoFrag()->lang('Dans %d jours à %s', floor($diff / 87840 * -1), $this->short_time());
				}
			}
			else if ($diff > 0)
			{
				if ($diff == strtoseconds('1 seconds'))
				{
					$output = NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 1);
				}
				else if ($diff <= strtoseconds('30 seconds'))
				{
					$output = NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', $diff, $diff);
				}
				else if ($diff < strtoseconds('45 seconds'))
				{
					$output = NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 30, 30);
				}
				else if ($diff < strtoseconds('50 seconds'))
				{
					$output = NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 45, 45);
				}
				else if ($diff < strtoseconds('55 seconds'))
				{
					$output = NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 50, 50);
				}
				else if ($diff < strtoseconds('2 minutes'))
				{
					$output = NeoFrag()->lang('Il y a environ une minute|Il y a %d minutes', 1);
				}
				else if ($diff <= strtoseconds('59 minutes'))
				{
					$output = NeoFrag()->lang('Il y a environ une minute|Il y a %d minutes', $diff = floor($diff / 60), $diff);
				}
				else if ($diff < strtoseconds('2 hours'))
				{
					$output = NeoFrag()->lang('Il y a environ une heure|Il y a %d heures', 1);
				}
				else if ($diff <= strtoseconds('23 hours'))
				{
					$output = NeoFrag()->lang('Il y a environ une heure|Il y a %d heures', $diff = floor($diff / 3660), $diff);
				}
				else if ($timestamp >= strtotime('yesterday'))
				{
					$output = NeoFrag()->lang('Hier à %s', $this->short_time());
				}
				else if ($timestamp >= strtotime('6 days ago midnight'))
				{
					$output = NeoFrag()->lang('%s dernier à %s', ucfirst($this->locale('%A')), $this->short_time());
				}
			}
			else
			{
				$output = NeoFrag()->lang('À l\'instant');
			}
		}

		return '<time datetime="'.$this->format().'">'.$output.'</time>';
	}

	public function __debugInfo()
	{
		return [
			'datetime' => $this->_datetime,
			'html'     => $this->__toString(),
			'sql'      => $this->sql()
		];
	}

	public function __clone()
	{
		$this->_datetime = clone $this->_datetime;
	}

	public function __sleep()
	{
		return ['_datetime'];
	}

	public function timezone($timezone = NULL)
	{
		$this->_timezone($timezone);
		$this->_datetime->setTimezone($timezone);
		return $this;
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

	public function diff($date = NULL)
	{
		if (!is_a($date, 'NF\NeoFrag\Libraries\Date'))
		{
			$date = $this->date($date, $this->_format, $this->_datetime->getTimezone());
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

	public function between($start, $end)
	{
		if (!is_a($start, 'NF\NeoFrag\Libraries\Date'))
		{
			$start = $this->date($start);
		}

		if (!is_a($end, 'NF\NeoFrag\Libraries\Date'))
		{
			$end = $this->date($end);
		}

		return $this->diff($start) != -1 && $this->diff($end) != 1;
	}

	public function sql()
	{
		return $this->format($this->_format ?: 'Y-m-d H:i:s');
	}

	public function interval($date = NULL)
	{
		if (!is_a($date, 'NF\NeoFrag\Libraries\Date'))
		{
			$date = $this->date($date, $this->_format, $this->_datetime->getTimezone());
		}

		return $this->_datetime->diff($date->_datetime);
	}

	public function timestamp()
	{
		return $this->_datetime->getTimestamp();
	}

	public function format($format = NULL)
	{
		return $this->_datetime->format($format ?: $this->_format ?: 'Y-m-d H:i:s.u P');
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

	public function short_date_time()
	{
		return $this->locale($this->config->lang->date()['short_date_time']);
	}

	public function long_date_time()
	{
		return $this->locale($this->config->lang->date()['long_date_time']);
	}

	public function short_time()
	{
		return $this->locale($this->config->lang->date()['short_time']);
	}

	public function long_time()
	{
		return $this->locale($this->config->lang->date()['long_time']);
	}

	protected function _timezone(&$timezone)
	{
		if (!is_a($timezone, 'DateTimeZone'))
		{
			if ($timezone)
			{
				$timezone = @timezone_open($timezone);
			}

			if (!$timezone)
			{
				if (!static::$default_timezone)
				{
					static::$default_timezone = timezone_open(date_default_timezone_get());
				}

				$timezone = static::$default_timezone;
			}
		}
	}
}
