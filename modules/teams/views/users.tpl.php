<form class="form-inline" action="<?php echo url($this->url->request) ?>" method="post">
	<div class="form-group">
		<label for="form_<?php echo $form_id ?>_user"><?php echo $this->lang('Joueur') ?></label>
		<select class="form-control ml-2" name="<?php echo $form_id ?>[user_id]">
			<option></option>
			<?php foreach ($users as $user): ?>
			<option value="<?php echo $user['user_id'] ?>"<?php if ($user['in_team']) echo ' disabled="disabled"' ?>><?php echo $user['username'] ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="form-group" style="margin: 0 20px;">
		<label for="form_<?php echo $form_id ?>_user"><?php echo $this->lang('Rôle') ?></label>
		<select class="form-control ml-2" name="<?php echo $form_id ?>[role_id]">
			<option></option>
			<?php foreach ($roles as $role): ?>
			<option value="<?php echo $role['role_id'] ?>"><?php echo $role['title'] ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<input type="submit" class="btn btn-primary" value="<?php echo $this->lang('Ajouter') ?>" />
</form>
