<form action="<?php echo $NeoFrag->config->base_url.$NeoFrag->config->request_url; ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="{form_id}[login]" placeholder="Identifiant" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="{form_id}[password]" placeholder="Mot de passe" />
	</div>
	<div class="text-right">
		<div class="pull-left checkbox no-margin">
			<label>
				<input type="checkbox" name="{form_id}[remember_me][]" value="on" checked="checked" />
				Se souvenir de moi
			</label>
		</div>
		<a href="{base_url}user/lost-password.html" class="btn btn-link">Mot de passe perdu ?</a>
		<input type="submit" class="btn btn-primary" value="Connexion" />
	</div>
</form>