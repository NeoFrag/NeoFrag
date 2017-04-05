<div class="form-group">
	<label for="live-editor-settings-title" class="col-md-3 control-label"><?php echo $this->lang('talk'); ?></label>
	<div class="col-md-5">
		<select class="form-control" name="settings[talk_id]">
		<?php foreach ($data['talks'] as $talk): ?>
			<option value="<?php echo $talk['talk_id']; ?>"<?php if (isset($data['settings']['talk_id']) && $data['settings']['talk_id'] == $talk['talk_id']) echo ' selected="selected"'; ?>><?php echo $talk['name']; ?></option>
		<?php endforeach; ?>
		</select>
	</div>
</div>