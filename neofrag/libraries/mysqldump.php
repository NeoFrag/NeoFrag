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

	public function dump($handle, $callback = NULL)
	{
		$this->db->lock($tables = $this->db->tables());

		if (is_callable($callback))
		{
			$total = $i = 0;

			foreach ($tables as $table)
			{
				$total += $this->db	->select('COUNT(*)')
									->from($table)
									->row();
			}
		}

		fwrite($handle, '-- NeoFrag '.NEOFRAG_VERSION.PHP_EOL.
						'-- https://neofr.ag'.PHP_EOL.
						'--'.PHP_EOL.
						'-- Host: '.$_SERVER['HTTP_HOST'].PHP_EOL.
						'-- Generation Time: '.date('r').PHP_EOL.
						'-- Server version: '.$this->db->get_info('server').' '.$this->db->get_info('version').PHP_EOL.
						'-- PHP Version: '.PHP_VERSION.PHP_EOL.PHP_EOL.
						'SET FOREIGN_KEY_CHECKS = 0;'.PHP_EOL.
						'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'.PHP_EOL.
						'SET TIME_ZONE = "'.$this->db->get_info('time_zone').'";'.PHP_EOL.PHP_EOL.
						'--'.PHP_EOL.
						'-- Database: '.self::_delimite($this->db->get_info('name')).PHP_EOL.
						'--'.PHP_EOL);

		foreach ($tables as $table)
		{
			fwrite($handle, PHP_EOL.
							'-- --------------------------------------------------------'.PHP_EOL.PHP_EOL.
							'--'.PHP_EOL.
							'-- Table structure for table '.self::_delimite($table).PHP_EOL.
							'--'.PHP_EOL.PHP_EOL.
							'DROP TABLE IF EXISTS '.self::_delimite($table).';'.PHP_EOL.
							$this->db->table_create($table).';'.PHP_EOL);

			$cols = $this->db->table_columns($table);

			$size = $dump = 0;

			$res = $this->db->query('SELECT * FROM '.self::_delimite($table))->results();
			while ($row = $this->db->fetch($res))
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
					fwrite($handle, PHP_EOL.'INSERT INTO '.self::_delimite($table).' ('.implode(', ', array_map([$this, '_delimite'], array_keys($cols))).') VALUES'.PHP_EOL);
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
						$values[] = '\''.$this->db->escape_string($value).'\'';
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

			$this->db->free($res);

			if ($size)
			{
				fwrite($handle, ';'.PHP_EOL);
			}
		}

		fwrite($handle, PHP_EOL.'-- --------------------------------------------------------'.PHP_EOL.PHP_EOL.'SET FOREIGN_KEY_CHECKS = 1;'.PHP_EOL);

		fclose($handle);

		$this->db->unlock($tables);
	}

	static private function _delimite($s)
	{
		return '`'.str_replace('`', '``', $s).'`';
	}
}
