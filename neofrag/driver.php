<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

abstract class Driver
{
	protected $info;

	public function __construct($hostname, $username, $password, $database)
	{
		$this->info = (object)[
			'hostname' => $hostname,
			'username' => $username,
			'password' => $password,
			'database' => $database
		];
	}

	abstract public function connect();
	abstract public function execute($request);
	abstract public function get_info();
	abstract public function get_size();
	abstract public function escape_string($string);
	abstract public function check_foreign_keys($check);
	abstract public function fetch($check, $type = 'assoc');
	abstract public function free($check);
	abstract public function lock($tables);
	abstract public function unlock($tables);
	abstract public function tables();
	abstract public function table_create($table);
	abstract public function table_columns($table);

	public function query($request)
	{
		static $check_foreign_keys;

		$request = new driver_query($this, $request);

		if (!$check_foreign_keys && empty($request->ignore_foreign_keys))
		{
			$this->check_foreign_keys($check_foreign_keys = TRUE);
		}
		else if ($check_foreign_keys !== FALSE && !empty($request->ignore_foreign_keys))
		{
			$this->check_foreign_keys($check_foreign_keys = FALSE);
		}

		if ($debug = NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
		{
			$time = microtime(TRUE);
		}

		$this->execute($request->build_sql());

		if ($debug)
		{
			$request->time = microtime(TRUE) - $time;
		}

		if ($debug || !empty($request->error))
		{
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);

			if (isset($backtrace[4]['file']))
			{
				$request->file = relative_path($backtrace[4]['file']);
				$request->line = $backtrace[4]['line'];
			}

			if ($debug)
			{
				NeoFrag()->debug('DB_QUERY', sprintf('%.3f', $request->time * 1000).'ms', $request->sql.(!empty($request->bind) ? ' '.json_encode($request->bind) : ''));
			}
			else
			{
				trigger_error($request->error.' ['.$request->sql.']'.(!empty($request->bind) ? ' '.json_encode($request->bind) : '').' in '.$request->file.' on line '.$request->line, E_USER_WARNING);
			}
		}

		return $request;
	}
}
