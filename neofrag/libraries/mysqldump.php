<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

/**
 * MySQL database dump.
 *
 * @author     David Grudl (http://davidgrudl.com)
 * @copyright  Copyright (c) 2008 David Grudl
 * @license    New BSD License
 * @version    1.0
 */
namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Mysqldump extends Library
{
	const MAX_SQL_SIZE = 50000;
	const DATA         = 1;
	const STRUCTURE    = 2;

	protected $_db;
	protected $_skip_auto_increment;
	protected $_skip_data;
	protected $_skip_structure;
	protected $_tables = [];

	public function __invoke($db)
	{
		$this->_db = $db;
		return $this;
	}

	public function skip_auto_increment()
	{
		$this->_skip_auto_increment = TRUE;
		return $this;
	}

	public function skip_data()
	{
		$this->_skip_data = TRUE;
		return $this;
	}

	public function skip_structure()
	{
		$this->_skip_structure = TRUE;
		return $this;
	}

	public function tables($tables)
	{
		if ($tables && !array_key_exists('data', $tables) && !array_key_exists('structure', $tables))
		{
			$tables = array_fill_keys(['data', 'structure'], $tables);
		}

		$this->_tables = $tables;

		return $this;
	}

	public function dump($handle, $callback = NULL)
	{
		$db = function(){
			return $this->db($this->_db) ?: $this->db;
		};

		$tables = [];

		foreach ($db()->tables() as $table)
		{
			$tables[$table] = 0;

			foreach (['data', 'structure'] as $action)
			{
				if ((!$this->{'_skip_'.$action} && (empty($this->_tables[$action]) || !in_array($table, $this->_tables[$action]))) ||
					($this->{'_skip_'.$action}  && !empty($this->_tables[$action]) && in_array($table, $this->_tables[$action])))
				{
					$tables[$table] |= constant('self::'.strtoupper($action));
				}
			}
		}

		$tables = array_filter($tables);

		fwrite($handle, '-- NeoFrag '.NEOFRAG_VERSION.PHP_EOL.
						'-- https://neofr.ag'.PHP_EOL.
						'--'.PHP_EOL.
						'-- Host: '.$_SERVER['HTTP_HOST'].PHP_EOL.
						'-- Generation Time: '.date('r').PHP_EOL.
						'-- Server version: '.$db()->get_info('server').' '.$db()->get_info('version').PHP_EOL.
						'-- PHP Version: '.PHP_VERSION.PHP_EOL.PHP_EOL.
						'SET FOREIGN_KEY_CHECKS = 0;'.PHP_EOL.
						'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'.PHP_EOL.
						'SET TIME_ZONE = "'.$db()->get_info('time_zone').'";'.PHP_EOL.
						'SET NAMES utf8;'.PHP_EOL.PHP_EOL.
						'--'.PHP_EOL.
						'-- Database: '.self::_delimite($db()->get_info('name')).PHP_EOL.
						'--'.PHP_EOL);

		if ($tables)
		{
			$db()->lock($tables);

			if (is_callable($callback))
			{
				$total = $i = 0;

				foreach ($tables as $table => $action)
				{
					if ($action & self::DATA)
					{
						$total += $db()->from($table)->count();
					}
				}
			}

			foreach ($tables as $table => $action)
			{
				if ($action & self::STRUCTURE)
				{
					$structure = $db()->table_create($table);

					if ($this->_skip_auto_increment)
					{
						$structure = preg_replace('/ AUTO_INCREMENT=\d+/', '', $structure);
					}

					fwrite($handle, PHP_EOL.
									'-- --------------------------------------------------------'.PHP_EOL.PHP_EOL.
									'--'.PHP_EOL.
									'-- Table structure for table '.self::_delimite($table).PHP_EOL.
									'--'.PHP_EOL.PHP_EOL.
									'DROP TABLE IF EXISTS '.self::_delimite($table).';'.PHP_EOL.
									$structure.';'.PHP_EOL);
				}

				if ($action & self::DATA)
				{
					$cols = $db()->table_columns($table);

					$cols_list = implode(', ', array_map([$this, '_delimite'], array_keys($cols)));

					$size = $dump = 0;

					$res = $db()->query('SELECT * FROM '.self::_delimite($table).' ORDER BY '.$cols_list)->results();
					while ($row = $db()->fetch($res))
					{
						if (!$dump)
						{
							$dump = TRUE;

							fwrite($handle, PHP_EOL.
											'--'.PHP_EOL.
											'-- Dumping data for table '.self::_delimite($table).PHP_EOL.
											'--'.PHP_EOL);
						}

						if ($size == 0)
						{
							fwrite($handle, PHP_EOL.'INSERT INTO '.self::_delimite($table).' ('.$cols_list.') VALUES'.PHP_EOL);
						}
						else
						{
							fwrite($handle, ','.PHP_EOL);
						}

						$values = [];

						foreach ($row as $key => $value)
						{
							if ($value === NULL)
							{
								$values[] = 'NULL';
							}
							elseif (preg_match('#^[^(]*(BYTE|COUNTER|SERIAL|INT|LONG$|CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER)#i', $cols[$key]))
							{
								$values[] = str_replace(',', '.', $value);
							}
							else
							{
								$values[] = '\''.utf8_string($db()->escape_string($value)).'\'';
							}
						}

						fwrite($handle, $line = '('.implode(', ', $values).')');

						$size += strlen($line);
						if ($size > self::MAX_SQL_SIZE)
						{
							fwrite($handle, ';'.PHP_EOL);
							$size = 0;
						}

						if (is_callable($callback))
						{
							$callback(++$i / $total * 100);
						}
					}

					$db()->free($res);

					if ($size)
					{
						fwrite($handle, ';'.PHP_EOL);
					}
				}
			}

			$db()->unlock($tables);
		}

		fwrite($handle, PHP_EOL.'-- --------------------------------------------------------'.PHP_EOL.PHP_EOL.'SET FOREIGN_KEY_CHECKS = 1;'.PHP_EOL);

		fclose($handle);
	}

	static private function _delimite($s)
	{
		return '`'.str_replace('`', '``', $s).'`';
	}
}
