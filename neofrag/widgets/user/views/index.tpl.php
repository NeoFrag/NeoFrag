<form action="<?php echo url('user/login.html'); ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[login]" placeholder="<?php echo i18n('username'); ?>" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="<?php echo $data['form_id']; ?>[password]" placeholder="<?php echo i18n('password'); ?>" />
	</div>
	<div class="text-right">
		<a href="<?php echo url('user/lost-password.html'); ?>" class="btn btn-link"><?php echo i18n('lost_password'); ?></a>
		<input type="hidden" name="<?php echo $data['form_id']; ?>[redirect]" value="<?php echo $NeoFrag->config->request_url; ?>" />
		<input type="hidden" name="<?php echo $data['form_id']; ?>[remember_me][]" value="on" />
		<input type="submit" class="btn btn-default" value="<?php echo i18n('login'); ?>" />
	</div>
</form>