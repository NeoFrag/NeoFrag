<?php if (!defined('NEOFRAG_CMS')) exit;

ini_set('display_errors', FALSE);

if (!empty($_GET['test_gzip']))
{
	die;
}

if (!empty($_SERVER['REQUEST_URI']) && preg_match('_/check/install$_', $_SERVER['REQUEST_URI']))
{
	$base  = @$_SERVER['REDIRECT_CONTEXT'];
	$base2 = substr($_SERVER['SCRIPT_NAME'], 0, -9);

	if (strpos($_SERVER['REQUEST_URI'], $base.$base2) === 0)
	{
		$base .= $base2;
	}
	else
	{
		$base .= '/';
	}

	file_put_contents('.htaccess_tmp', str_replace('%BASE%', $base, file_get_contents('install/htaccess.txt')));

	exit('OK');
}

if (file_exists('.htaccess') && !file_exists('.htaccess_tmp'))
{
	$i = 0;

	do
	{
		$name = preg_replace('/_0$/', '', '.htaccess_old_'.$i++);
	}
	while (file_exists($name));

	rename('.htaccess', $name);
}

$default_lang = $lang = 'fr';
$i18n = [];

if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && preg_match_all('/([a-zA-Z-]+)(?:;q=([0-9.]+))?,?/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches, PREG_SET_ORDER))
{
	$accepted = [];

	foreach ($matches as $match)
	{
		$accepted[$match[1]] = isset($match[2]) ? (float)$match[2] : 1;
	}

	arsort($accepted);

	foreach ($accepted as $name => $q)
	{
		if (file_exists('install/langs/'.$name.'.php'))
		{
			$lang = $name;
			$i18n = include 'install/langs/'.$lang.'.php';
			break;
		}
		else if ($default_lang == $name)
		{
			$lang = $name;
			break;
		}
	}
}

function lang($locale)
{
	global $default_lang, $lang, $i18n;

	if ($default_lang != $lang && array_key_exists($hash = hash('crc32b', $locale), $i18n))
	{
		$locale = $i18n[$hash];
	}

	return $locale;
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
{
	require_once 'install/ajax.php';
	die;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
<meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible">
<meta name="theme-color" content="#2b373a">
<title><?php echo lang('Installation').' @NeoFrag '.NEOFRAG_VERSION ?></title>
<link rel="shortcut icon" href="images/favicon.png" type="image/png">
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="install/install.css" type="text/css">
<link rel="stylesheet" href="css/icons/fontawesome.min.css" type="text/css">
</head>
<body>
	<a class="logo" href="https://neofr.ag" target="_blank"><img src="install/logo.png" alt="" /></a>
	<div class="container">
		<?php if (!file_exists('install/db.txt')): ?>
			<section class="step check-init">
				<div class="row">
					<div class="col-12">
						<div class="heading">
							<a href="#" class="btn btn-action btn-success invisible float-right" data-action="next-step"><?php echo lang('Continuer') ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
							<h1><?php echo lang('Installation') ?></h1>
							<p class="lead"><?php echo lang('Bienvenue ! Laissez-vous guider pour installer votre nouveau site avec NeoFrag') ?></p>
						</div>
					</div>
				</div>
				<div class="row first-check">
					<div class="col-12">
						<div class="legend">
							<div class="checking">
								<i class="fas fa-circle-notch fa-spin fa-3x float-left mr-3"></i> <?php echo lang('Avant de commencer, nous devons vérifer la compatibilité de votre système.<br>Merci de patienter quelques instants...') ?>
							</div>
							<div class="errors d-none">
								<i class="fas fa-dizzy fa-3x float-left mr-3 text-danger"></i> <?php echo lang('<b>Oops...</b> L\'installation est impossible !<br>Veuillez résoudre les <span class="text-danger"><i class="fas fa-times"></i> erreurs bloquantes</span> indiquées ci-dessous afin de poursuivre l\'installation...') ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row first-check-errors d-none">
					<div class="col-12">
						<ul class="list-group no-margin">
							<li class="list-group-item d-none">
								<div class="float-right">
									<ul class="list-inline no-margin">
									</ul>
								</div>
								{icon} {title}
							</li>
						</ul>
					</div>
				</div>
			</section>
			<section class="step" data-step="db">
				<div class="row">
					<div class="col-12">
						<div class="heading">
							<a href="#" class="btn btn-action btn-success invisible float-right" data-action="next-step"><?php echo lang('Continuer') ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
							<h1><?php echo lang('Étape 1') ?></h1>
							<p class="lead"><?php echo lang('Connexion à votre base de données') ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<form autocomplete="off" novalidate>
							<div class="form-group row">
								<label for="host" class="col-sm-4 control-label"><?php echo lang('Serveur') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="host" name="host" value="localhost" required>
									<small class="form-text text-muted">
										<?php echo lang('Correspond à l\'adresse de votre base de données. Si vous ne la connaissez pas, demandez cette information auprès de l\'hébergeur de votre site') ?>
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label for="dbname" class="col-sm-4 control-label"><?php echo lang('Nom de la base de données') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="dbname" name="dbname" required>
									<small class="form-text text-muted">
										<?php echo lang('Nom de la base de données sur laquelle vous souhaitez installer NeoFrag') ?>
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label for="user" class="col-sm-4 control-label"><?php echo lang('Identifiant') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="user" name="user">
									<small class="form-text text-muted">
										<?php echo lang('Votre nom d\'utilisateur d\'accès à votre base de données') ?>
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label for="password" class="col-sm-4 control-label"><?php echo lang('Mot de passe') ?></label>
								<div class="col-sm-8">
									<input type="password" class="form-control" id="password" name="password">
									<small class="form-text text-muted">
										<?php echo lang('Votre mot de passe d\'accès à votre base de données') ?>
									</small>
								</div>
							</div>
							<div class="form-group row">
								<div class="offset-sm-4 col-sm-8">
									<i class="text-muted"><?php echo lang('* Toutes les informations marquées d\'une étoile sont requises') ?></i>
								</div>
							</div>
							<div class="form-group">
								<div class="offset-sm-4 col-sm-8">
									<button type="submit" class="btn btn-info" data-loading-text="<?php echo lang('Vérification en cours...') ?>"><?php echo lang('Tester la connexion') ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
			<section class="step">
				<div class="text-center heading blinking">
					<h1><i class="fas fa-circle-notch fa-spin mb-5"></i> <?php echo lang('Installation en cours') ?></h1>
				</div>
			</section>
		<?php else: ?>
			<section class="step" data-step="user">
				<div class="row">
					<div class="col">
						<div class="heading">
							<h1><?php echo lang('Étape 2') ?></h1>
							<p class="lead"><?php echo lang('Création de votre compte administrateur') ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1 offset-md-1">
						<form novalidate>
							<div class="form-group row">
								<label for="username" class="col-sm-4 control-label"><?php echo lang('Identifiant') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="username" name="username" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="password" class="col-sm-4 control-label"><?php echo lang('Mot de passe') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="password" class="form-control" id="password" name="password" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="password2" class="col-sm-4 control-label"><?php echo lang('Confirmer le mot de passe') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="password" class="form-control" id="password2" name="password2" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="email" class="col-sm-4 control-label"><?php echo lang('Adresse e-mail') ?> <em>*</em></label>
								<div class="col-sm-8">
									<input type="mail" class="form-control" id="email" name="email" required>
								</div>
							</div>
							<div class="form-group row">
								<div class="offset-sm-4 col-sm-8">
									<i class="text-muted"><?php echo lang('* Toutes les informations marquées d\'une étoile sont requises') ?></i>
								</div>
							</div>
							<div class="form-group">
								<div class="offset-sm-4 col-sm-8">
									<button type="submit" class="btn btn-info" data-loading-text="<?php echo lang('Enregistrement en cours...') ?>"><?php echo lang('Valider') ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
			<section class="step">
				<div class="row">
					<div class="col-md-12">
						<div class="heading">
							<h1><?php echo lang('Félicitation !') ?></h1>
							<h2><?php echo lang('Votre site est maintenant prêt à être configuré !') ?></h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="https://neofr.ag" class="btn btn-action btn-link" target="_blank"><?php echo lang('Site NeoFrag') ?><i class="fa fa-angle-right"></i></a>
						<a href="admin/settings" class="btn btn-action btn-info btn-finished"><?php echo lang('Configurer mon site') ?><i class="fa fa-angle-right"></i></a>
					</div>
				</div>
			</section>
		<?php endif ?>
	</div>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="install/install.js"></script>
</body>
</html>
<?php die;
