<form action="<?php echo url($NeoFrag->config->request_url); ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[login]" placeholder="<?php echo i18n('username'); ?>" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="<?php echo $data['form_id']; ?>[password]" placeholder="<?php echo i18n('password'); ?>" />
	</div>
	<div class="text-right">
		<div class="pull-left checkbox no-margin">
			<label>
				<input type="checkbox" name="<?php echo $data['form_id']; ?>[remember_me][]" value="on" checked="checked" />
				<?php echo i18n('remember_me'); ?>
			</label>
		</div>
		<a href="<?php echo url('user/lost-password.html'); ?>" class="btn btn-link"><?php echo i18n('forgot_password'); ?></a>
		<input type="hidden" name="<?php echo $data['form_id']; ?>[redirect]" value="" />
		<input type="submit" class="btn btn-primary" value="<?php echo i18n('login'); ?>" />
	</div>
</form>