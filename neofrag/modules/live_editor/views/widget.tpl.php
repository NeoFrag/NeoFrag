<form id="live-editor-settings-form" class="form-horizontal">
	<div class="form-group">
		<label for="live-editor-settings-widget" class="col-md-3 control-label"><?php echo $this->lang('widget'); ?></label>
		<div class="col-md-5">
			<select id="live-editor-settings-widget" class="form-control" name="widget">
			<?php foreach ($data['widgets'] as $name => $widget): ?>
				<option value="<?php echo $name; ?>"<?php if ($name == $data['widget']) echo ' selected="selected"'; ?>><?php echo $widget; ?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group"<?php if (empty($data['types'][$data['widget']])) echo ' style="display: none;"'; ?>>
		<label for="live-editor-settings-type" class="col-md-3 control-label"><?php echo $this->lang('type'); ?></label>
		<div class="col-md-5">
			<select id="live-editor-settings-type" class="form-control" name="type">
			<?php foreach ($data['types'] as $widget => $types): ?>
				<?php foreach ($types as $name => $type): ?>
					<option value="<?php echo $name; ?>" data-widget="<?php echo $widget; ?>"<?php if ($widget != $data['widget']) echo ' style="display: none;"'; else if ($name == $data['type']) echo ' selected="selected"'; ?>><?php echo $type; ?></option>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group"<?php if ($data['widget'] == 'module') echo ' style="display: none;"'; ?>>
		<label for="live-editor-settings-title" class="col-md-3 control-label"><?php echo $this->lang('title'); ?></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="live-editor-settings-title" name="title" value="<?php echo $data['title']; ?>" placeholder="<?php echo $this->lang('default_title'); ?>" />
		</div>
	</div>
	<div id="live-editor-settings" data-widget-id="<?php echo $data['widget_id']; ?>" data-original-widget="<?php echo $data['widget']; ?>" data-original-type="<?php echo $data['type']; ?>"></div>
</form>