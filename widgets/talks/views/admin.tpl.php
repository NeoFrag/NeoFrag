<div class="form-group">
	<label for="live-editor-settings-title" class="col-3 control-label"><?php echo $this->lang('Discussion') ?></label>
	<div class="col-5">
		<select class="form-control" name="settings[talk_id]">
		<?php foreach ($talks as $talk): ?>
			<option value="<?php echo $talk['talk_id'] ?>"<?php if (isset($settings['talk_id']) && $settings['talk_id'] == $talk['talk_id']) echo ' selected="selected"' ?>><?php echo $talk['name'] ?></option>
		<?php endforeach ?>
		</select>
	</div>
</div>
