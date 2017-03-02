<form class="form-inline" action="<?php echo url($this->url->request); ?>" method="post">
	<div class="form-group">
		<label for="form_<?php echo $data['form_id']; ?>_user"><?php echo $this->lang('player_single'); ?></label>
		<select class="form-control" name="<?php echo $data['form_id']; ?>[user_id]">
			<option></option>
			<?php foreach ($data['users'] as $user): ?>
			<option value="<?php echo $user['user_id']; ?>"<?php if ($user['in_team']) echo ' disabled="disabled"'; ?>><?php echo $user['username']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group" style="margin: 0 20px;">
		<label for="form_<?php echo $data['form_id']; ?>_user"><?php echo $this->lang('role'); ?></label>
		<select class="form-control" name="<?php echo $data['form_id']; ?>[role_id]">
			<option></option>
			<?php foreach ($data['roles'] as $role): ?>
			<option value="<?php echo $role['role_id']; ?>"><?php echo $role['title']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type="submit" class="btn btn-primary" value="<?php echo $this->lang('add'); ?>" />
</form>