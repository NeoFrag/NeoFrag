<form action="<?php echo url('user/login.html'); ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[login]" placeholder="Identifiant" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="<?php echo $data['form_id']; ?>[password]" placeholder="Mot de passe" />
	</div>
	<div class="text-right">
		<a href="<?php echo url('user/lost-password.html'); ?>" class="btn btn-link">Mot de passe perdu ?</a>
		<input type="hidden" name="<?php echo $data['form_id']; ?>[redirect]" value="<?php echo $NeoFrag->config->request_url; ?>" />
		<input type="hidden" name="<?php echo $data['form_id']; ?>[remember_me][]" value="on" />
		<input type="submit" class="btn btn-default" value="Connexion" />
	</div>
</form>