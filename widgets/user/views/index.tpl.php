<form action="<?php echo url('user/login') ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="<?php echo $data['form_id'] ?>[login]" placeholder="<?php echo $this->lang('Identifiant') ?>" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="<?php echo $data['form_id'] ?>[password]" placeholder="<?php echo $this->lang('Mot de passe') ?>" />
	</div>
	<div class="text-right">
		<a href="<?php echo url('user/lost-password') ?>" class="btn btn-link"><?php echo $this->lang('Mot de passe perdu ?') ?></a>
		<input type="hidden" name="<?php echo $data['form_id'] ?>[redirect]" value="<?php echo $this->url->request ?>" />
		<input type="hidden" name="<?php echo $data['form_id'] ?>[remember_me][]" value="on" />
		<input type="submit" class="btn btn-default" value="<?php echo $this->lang('Connexion') ?>" />
	</div>
</form>
