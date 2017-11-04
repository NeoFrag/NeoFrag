<?php

define('NEOFRAG_DEBUG_BAR',  FALSE);
define('NEOFRAG_SAFE_MODE',  FALSE);
define('NEOFRAG_LOGS',       FALSE);
define('NEOFRAG_LOGS_DB',    FALSE);
define('NEOFRAG_LOGS_I18N',  FALSE);

error_reporting(E_ALL);

ini_set('error_log',              'logs/php.log');
ini_set('display_errors',         TRUE);
ini_set('default_charset',        'UTF-8');
ini_set('mbstring.func_overload', 7);

mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');
