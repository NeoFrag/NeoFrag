<form action="<?php echo url($this->url->request); ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[login]" placeholder="<?php echo $this->lang('username'); ?>" />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="<?php echo $data['form_id']; ?>[password]" placeholder="<?php echo $this->lang('password'); ?>" />
	</div>
	<div class="text-right">
		<div class="pull-left checkbox no-margin">
			<label>
				<input type="checkbox" name="<?php echo $data['form_id']; ?>[remember_me][]" value="on" checked="checked" />
				<?php echo $this->lang('remember_me'); ?>
			</label>
		</div>
		<a href="<?php echo url('user/lost-password'); ?>" class="btn btn-link"><?php echo $this->lang('forgot_password'); ?></a>
		<input type="hidden" name="<?php echo $data['form_id']; ?>[redirect]" value="" />
		<input type="submit" class="btn btn-primary" value="<?php echo $this->lang('login'); ?>" />
	</div>
</form>