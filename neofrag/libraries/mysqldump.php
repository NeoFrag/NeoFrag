<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

/**
 * MySQL database dump.
 *
 * @author     David Grudl (http://davidgrudl.com)
 * @copyright  Copyright (c) 2008 David Grudl
 * @license    New BSD License
 * @version    1.0
 */
class MySQLDump extends Library
{
	const MAX_SQL_SIZE = 1e6;

	const NONE = 0;
	const DROP = 1;
	const CREATE = 2;
	const DATA = 4;
	const TRIGGERS = 8;
	const ALL = 15; // DROP | CREATE | DATA | TRIGGERS

	/** @var array */
	public $tables = array(
		'*' => self::ALL,
	);


	/**
	 * Saves dump to the file.
	 * @param  string filename
	 * @return void
	 */
	public function dump($handle, $callback = NULL)
	{
		$tables = $views = array();

		foreach (array_map('array_values', $this->db->query('SHOW FULL TABLES LIKE "nf\_%"')->get()) as $row) {
			if ($row[1] === 'VIEW') {
				$views[] = $row[0];
			} else {
				$tables[] = $row[0];
			}
		}

		$tables = array_merge($tables, $views); // views must be last

		$this->db->lock($tables);

		$db = $this->db->query('SELECT DATABASE()')->row();
		fwrite($handle, "-- Created at " . date('j.n.Y G:i') . " using David Grudl MySQL Dump Utility\r\n"
			. (isset($_SERVER['HTTP_HOST']) ? "-- Host: $_SERVER[HTTP_HOST]\r\n" : '')
			. "-- Server: " . $this->db->get_info('server') . " " . $this->db->get_info('version'). "\r\n"
			. "-- Database: " . $db . "\r\n"
			. "\r\n"
			. "SET NAMES utf8;\r\n"
			. "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\r\n"
			. "SET FOREIGN_KEY_CHECKS=0;\r\n"
		);

		if (is_callable($callback)) {
			$total = $i = 0;
			foreach ($tables as $table) {
				$total += $this->db->query("SELECT COUNT(*) FROM {$this->delimite($table)}")->row();
			}
		}

		foreach ($tables as $table) {
			$delTable = $this->delimite($table);
			$row = $this->db->query("SHOW CREATE TABLE $delTable")->row();

			fwrite($handle, "-- --------------------------------------------------------\r\n\r\n");

			$mode = isset($this->tables[$table]) ? $this->tables[$table] : $this->tables['*'];
			$view = isset($row['Create View']);

			if ($mode & self::DROP) {
				fwrite($handle, 'DROP ' . ($view ? 'VIEW' : 'TABLE') . " IF EXISTS $delTable;\r\n\r\n");
			}

			if ($mode & self::CREATE) {
				fwrite($handle, $row[$view ? 'Create View' : 'Create Table'] . ";\r\n\r\n");
			}

			if (!$view && ($mode & self::DATA)) {
				$numeric = array();
				$cols = array();
				foreach ($this->db->query("SHOW COLUMNS FROM $delTable")->get() as $row) {
					$col = $row['Field'];
					$cols[] = $this->delimite($col);
					$numeric[$col] = (bool) preg_match('#^[^(]*(BYTE|COUNTER|SERIAL|INT|LONG$|CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER)#i', $row['Type']);
				}
				$cols = '(' . implode(', ', $cols) . ')';

				$size = 0;
				$res = $this->db->query("SELECT * FROM $delTable")->results();
				while ($row = $this->db->fetch($res)) {
					$s = '(';
					foreach ($row as $key => $value) {
						if ($value === NULL) {
							$s .= "NULL,\t";
						} elseif ($numeric[$key]) {
							$s .= $value . ",\t";
						} else {
							$s .= "'" . $this->db->escape_string($value) . "',\t";
						}
					}

					if ($size == 0) {
						$s = "INSERT INTO $delTable $cols VALUES\r\n$s";
					} else {
						$s = ",\r\n$s";
					}

					$len = strlen($s) - 1;
					$s[$len - 1] = ')';
					fwrite($handle, $s, $len);

					$size += $len;
					if ($size > self::MAX_SQL_SIZE) {
						fwrite($handle, ";\r\n");
						$size = 0;
					}

					if (is_callable($callback)) {
						$callback(++$i / $total * 100);
					}
				}

				$this->db->free($res);

				if ($size) {
					fwrite($handle, ";\r\n");
				}
				fwrite($handle, "\r\n");
			}

			fwrite($handle, "\r\n");
		}

		fwrite($handle, "-- THE END\r\n");
		fclose($handle);

		$this->db->unlock($tables);
	}

	private function delimite($s)
	{
		return '`' . str_replace('`', '``', $s) . '`';
	}

}

/*
NeoFrag Alpha 0.1.5
./neofrag/libraries/mysqldump.php
*/