<form action="<?php echo url($this->url->request) ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="<?php echo $form_id ?>[login]" placeholder="<?php echo $this->lang('Identifiant') ?>" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="<?php echo $form_id ?>[password]" placeholder="<?php echo $this->lang('Mot de passe') ?>" />
	</div>
	<div class="text-right">
		<div class="pull-left checkbox no-margin">
			<label>
				<input type="checkbox" name="<?php echo $form_id ?>[remember_me][]" value="on" checked="checked" />
				<?php echo $this->lang('Se souvenir de moi') ?>
			</label>
		</div>
		<a href="<?php echo url('user/lost-password') ?>" class="btn btn-link"><?php echo $this->lang('Mot de passe oublié ?') ?></a>
		<input type="hidden" name="<?php echo $form_id ?>[redirect]" value="" />
		<input type="submit" class="btn btn-primary" value="<?php echo $this->lang('Connexion') ?>" />
	</div>
</form>
