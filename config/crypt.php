<?php

$chars         = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9), str_split('~!@#$%^&*()-_=+[]{};:,.<>/?'));
$crypt['key'] = '';

foreach (range(1, 130) as $i)
{
	$crypt['key'] .= $chars[array_rand($chars)];
}

file_put_contents('./config/crypt.php', utf8_string("<?php\n\n\$crypt['key'] = '".$crypt['key']."';\n"));
