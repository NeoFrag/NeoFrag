<form action="{base_url}user/login.html" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="{form_id}[login]" placeholder="Identifiant" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="{form_id}[password]" placeholder="Mot de passe" />
	</div>
	<div class="text-right">
		<a href="{base_url}user/lost-password.html" class="btn btn-link">Mot de passe perdu ?</a>
		<input type="hidden" name="{form_id}[redirect]" value="<?php echo $NeoFrag->config->request_url; ?>" />
		<input type="hidden" name="{form_id}[remember_me][]" value="on" />
		<input type="submit" class="btn btn-default" value="Connexion" />
	</div>
</form>