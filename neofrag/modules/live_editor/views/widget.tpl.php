<form id="live-editor-settings-form" class="form-horizontal">
	<div class="form-group">
		<label for="live-editor-settings-widget" class="col-md-3 control-label">Widget</label>
		<div class="col-md-5">
			<select id="live-editor-settings-widget" class="form-control" name="widget">
			<?php foreach ($data['widgets'] as $name => $widget): ?>
				<option value="<?php echo $name; ?>"<?php if ($name == $data['widget']) echo ' selected="selected"'; ?>><?php echo $widget; ?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="live-editor-settings-type" class="col-md-3 control-label">Type</label>
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
		<label for="live-editor-settings-title" class="col-md-3 control-label">Titre</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="live-editor-settings-title" name="title" value="{title}" placeholder="Titre par défaut" />
		</div>
	</div>
	<div id="live-editor-settings" data-widget-id="{widget_id}" data-original-widget="{widget}" data-original-type="{type}"></div>
</form>