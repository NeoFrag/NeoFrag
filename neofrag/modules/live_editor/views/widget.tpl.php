<form id="live-editor-settings-form" class="form-horizontal">
	<div class="form-group">
		<label for="live-editor-settings-widget" class="col-md-3 control-label"><?php echo i18n('widget'); ?></label>
		<div class="col-md-5">
			<select id="live-editor-settings-widget" class="form-control" name="widget">
			<?php foreach ($data['widgets'] as $name => $widget): ?>
				<option value="<?php echo $name; ?>"<?php if ($name == $data['widget']) echo ' selected="selected"'; ?>><?php echo $widget; ?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="live-editor-settings-type" class="col-md-3 control-label"><?php echo i18n('type'); ?></label>
		<div class="col-md-5">
			<select id="live-editor-settings-type" class="form-control" name="type">
			<?php foreach ($data['types'] as $widget => $types): ?>
				<?php foreach ($types as $name => $type): ?>
					<option value="<?php echo $name; ?>" data-widget="<?php echo $widget; ?>"<?php if ($widget != $data['widget']) echo ' style="display: none;"'; ?><?php if ($widget == $data['widget'] && $name == $data['type']) echo ' selected="selected"'; ?>><?php echo $type; ?></option>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="live-editor-settings-title" class="col-md-3 control-label"><?php echo i18n('title'); ?></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="live-editor-settings-title" name="title" value="<?php echo $data['title']; ?>" placeholder="<?php echo i18n('default_title'); ?>" />
		</div>
	</div>
	<div id="live-editor-settings" data-widget-id="<?php echo $data['widget_id']; ?>" data-original-widget="<?php echo $data['widget']; ?>" data-original-type="<?php echo $data['type']; ?>"></div>
</form>