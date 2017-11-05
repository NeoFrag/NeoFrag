<?php

$chars            = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9), str_split('~!@#$%^&*()-_=+[]{};:,.<>/?'));
$password['salt'] = '';

foreach (range(1, 64) as $i)
{
	$password['salt'] .= $chars[array_rand($chars)];
}

file_put_contents('./config/password.php', utf8_string("<?php\n\n\$password['salt'] = '".$password['salt']."';\n"));
