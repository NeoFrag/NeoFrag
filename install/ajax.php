<?php if (!defined('NEOFRAG_CMS')) exit;

$step = array_key_exists('step', $_GET) ? $_GET['step'] : '';
$output = [];

if ($step == 'check')
{
	$checks = [];

	if (@copy('install/htaccess_check.txt', '.htaccess'))
	{
		$checks[] = function(){
			if (file_get_contents(preg_replace('/index\.php$/', '', $_SERVER['HTTP_REFERER']).'check/install') == 'OK')
			{
				$icon = 'success';
				$info = [lang('OK')];
			}
			else
			{
				$icon = 'danger';
				$info = [lang('Requis')];
				unlink('.htaccess');
			}

			return [
				'title' => lang('Réécriture d\'URL'),
				'info'  => $info,
				'icon'  => $icon
			];
		};
	}
	else
	{
		$checks[] = function(){
			return [
				'title' => lang('Droits d\'écriture'),
				'info'  => [lang('Requis')],
				'icon'  => 'danger'
			];
		};
	}

	$checks[] = function(){
		if (@mail('test@neofr.ag', 'email_check', ''))
		{
			$icon = 'success';
			$info = [lang('OK')];
		}
		else
		{
			$icon = 'warning';
			$info = [lang('À configurer')];
		}

		return [
			'title' => lang('Envoi d\'email'),
			'info'  => $info,
			'icon'  => $icon
		];
	};

	$checks[] = function(){
		if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off')
		{
			$icon = 'success';
			$info = [lang('OK')];
		}
		else
		{
			$icon = 'warning';
			$info = [lang('À configurer')];
		}

		return [
			'title' => lang('Connexion sécurisée HTTPS'),
			'info'  => $info,
			'icon'  => $icon
		];
	};

	if (extension_loaded('curl'))
	{
		$checks[] = function(){
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,            $_SERVER['HTTP_REFERER'].'?test_gzip=1');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER,         TRUE);
			curl_setopt($ch, CURLOPT_ENCODING,       '');
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$response = curl_exec($ch);

			curl_close($ch);

			if (preg_match('/^Content-Encoding:.*(x-gzip|gzip|compress|deflate|br)/im', $response, $match))
			{
				$icon = 'success';
				$info = [$match[1] => lang('OK')];
			}
			else
			{
				$icon = 'warning';
				$info = [lang('À configurer')];
			}

			return [
				'title' => lang('Compression HTTP'),
				'info'  => $info,
				'icon'  => $icon
			];
		};

		$checks[] = function(){
			$output = [];

			$get = function($ssl = TRUE) use (&$output){
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, 'https://neofr.ag/version.json?v=last&install='.urlencode(NEOFRAG_VERSION));
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);

				if (!$ssl)
				{
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				}

				$content = json_decode(curl_exec($ch));

				if ($content && $content->neofrag->version != NEOFRAG_VERSION)
				{
					$output[] =[
						'title' => 'NeoFrag '.NEOFRAG_VERSION,
						'info'  => [
							lang('Dernière version') => $content->neofrag->version
						],
						'icon'  => 'danger'
					];
				}

				curl_close($ch);

				return (bool)$content;
			};

			if ($get())
			{
				$icon = 'success';
				$info = [lang('OK')];
			}
			else if ($get(FALSE))
			{
				$icon = 'warning';
				$info = [lang('Certificats SSL manquants')];
			}
			else
			{
				$icon = 'danger';
				$info = [lang('Manquante')];
			}

			return array_merge([[
				'title' => lang('Liaison avec https://neofr.ag'),
				'info'  => $info,
				'icon'  => $icon
			]], $output);
		};
	}

	$checks[] = function(){
		$icon = 'success';
		$info = [];

		//https://www.php.net/supported-versions.php
		$minimal_required = 5.6;
		$current          = 7.4;
		$last_end_of_life = 7.1;

		if (version_compare(PHP_VERSION, $minimal_required, '<'))
		{
			$icon = 'danger';
			$info = [
				lang('Minimum requis')      => $minimal_required,
				lang('Version recommandée') => $current
			];
		}
		else if (version_compare(PHP_VERSION, $last_end_of_life, '<='))
		{
			$icon = 'warning';
			$info = [
				lang('Version recommandée') => $current
			];
		}
		else if (version_compare(PHP_VERSION, $current, '<'))
		{
			$icon = 'info';
			$info = [
				lang('Version recommandée') => $current
			];
		}

		return [
			'title' => 'PHP '.PHP_VERSION,
			'info'  => $info,
			'icon'  => $icon
		];
	};

	$extensions = [
		'curl'     => ['Curl',     TRUE],
		'gd'       => ['Gd2',      TRUE],
		'mbstring' => ['Mbstring', TRUE],
		'mysqli'   => ['Mysqli',   TRUE],
		'intl'     => ['Intl',     FALSE],
		'zip'      => ['Zip',      TRUE]
	];

	$errors = 0;

	foreach ($extensions as $name => list($title, $required))
	{
		if (!extension_loaded($name))
		{
			$errors++;

			if (!$required)
			{
				$icon = 'warning';
				$info = [lang('Recommandée')];
			}
			else
			{
				$icon = 'danger';
				$info = [lang('Requise')];
			}

			$checks[] = function() use ($title, $info, $icon){
				return [
					'title' => lang('Extension PHP').' <i>'.$title.'</i>',
					'info'  => $info,
					'icon'  => $icon
				];
			};
		}
	}

	if (!$errors)
	{
		$checks[] = function(){
			return [
				'title' => lang('Extensions PHP'),
				'info'  => [lang('OK')],
				'icon'  => 'success'
			];
		};
	}

	$output = [];

	array_walk($checks, function($a) use (&$output){
		$a = $a();

		if (array_key_exists('title', $a))
		{
			$output[] = $a;
		}
		else
		{
			$output = array_merge($output, $a);
		}
	});
}
else if ($step == 'db')
{
	$ok = TRUE;

	foreach (['host', 'user', 'dbname'] as $var)
	{
		if ($_POST[$var] === '')
		{
			$output['errors'][$var] = '';

			if ($var == 'dbname')
			{
				$_POST['dbname'] = NULL;
			}
			else
			{
				$ok = FALSE;
			}
		}
	}

	if ($ok)
	{
		$mysqli = mysqli_init();
		$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
		@$mysqli->real_connect($_POST['host'], $_POST['user'], $_POST['password'], $_POST['dbname']);

		if ($mysqli->connect_errno)
		{
			if (in_array($mysqli->connect_errno, [2002, 2006]))
			{
				$output['errors']['host'] = '';
			}
			else if ($mysqli->connect_errno == 1044)
			{
				$output['errors']['host']     = 'ok';
				$output['errors']['user']     = lang('Autorisation manquante pour accéder à la base de donnée');
				$output['errors']['password'] = '';
				$output['errors']['dbname']   = 'ok';
			}
			else if (in_array($mysqli->connect_errno, [1045, 1698]))
			{
				$output['errors']['host']     = 'ok';
				$output['errors']['user']     = '';
				$output['errors']['password'] = '';
			}
			else if (in_array($mysqli->connect_errno, [1102, 1049]))
			{
				$output['errors']['host']     = 'ok';
				$output['errors']['user']     = 'ok';
				$output['errors']['password'] = 'ok';
				$output['errors']['dbname']   = '';
			}
		}
		else
		{
			preg_match_all('/^DROP TABLE IF EXISTS `(.+?)`;/m', file_get_contents('install/DATABASE.sql'), $matches);

			$empty_base = TRUE;

			if ($result = $mysqli->query('SHOW TABLE STATUS LIKE "nf\_%"'))
			{
				while ($table = $result->fetch_object())
				{
					if (in_array($table->Name, $matches[1]))
					{
						$empty_base = FALSE;
						break;
					}
				}

				$result->close();
			}

			if (!$empty_base)
			{
				$output['errors']['host']     = 'ok';
				$output['errors']['user']     = 'ok';
				$output['errors']['password'] = 'ok';
				$output['errors']['dbname']   = lang('Cette base de données contient déjà une installation NeoFrag');
			}
			else
			{
				$output = 'ok';

				if (!empty($_GET['install']))
				{
					$config = file_get_contents('config/db.php');

					foreach ([
								'host'     => 'hostname',
								'user'     => 'username',
								'password' => '',
								'dbname'   => 'database'
							]
						as $key => $name)
					{
						$config = preg_replace_callback('/(\''.($name ?: $key).'\' +=> (\'?))(.*?)(\2,?)$/m', function($match) use ($key){
							unset($match[0], $match[2]);
							$match[3] = addcslashes($_POST[$key], '\'');
							return implode($match);
						}, $config);
					}

					file_put_contents('config/db.php', $config);

					require_once 'lib/SqlFormatter/SqlFormatter.php';

					foreach (SqlFormatter::splitQuery(file_get_contents('install/DATABASE.sql')) as $query)
					{
						$mysqli->query($query);
					}

					touch('install/db.txt');
				}
			}
		}
	}
}
else if ($step == 'user')
{
	foreach (['username', 'password', 'password2', 'email'] as $var)
	{
		$$var = post($var);
		$output['errors'][$var] = is_empty($$var) ? '' : 'ok';
	}

	if ($password != $password2)
	{
		$output['errors']['password']  = '';
		$output['errors']['password2'] = '';
	}

	if (!is_valid_email($email))
	{
		$output['errors']['email'] = '';
	}

	$ok = TRUE;

	foreach ($output['errors'] as $value)
	{
		if ($value != 'ok')
		{
			$ok = FALSE;
			break;
		}
	}

	if ($ok)
	{
		$user = NeoFrag()	->module('user')
							->model2('user', 1)
							->set_password($password)
							->set('username', utf8_htmlentities($username))
							->set('email',    utf8_htmlentities($email))
							->update();

		NeoFrag()->session->login($user);

		require_once 'neofrag/helpers/dir.php';
		unlink('.htaccess');
		rename('.htaccess_tmp', '.htaccess');
		dir_remove('install');

		$output = 'ok';
	}
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($output);
