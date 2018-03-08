<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function debug_exit()
{
	if ($args = func_get_args())
	{
		var_dump($args);
	}

	var_dump(round((microtime(TRUE) - NEOFRAG_TIME) * 1000, 2).' ms', ceil((memory_get_peak_usage() - NEOFRAG_MEMORY) / 1024).' kB');

	exit;
}
