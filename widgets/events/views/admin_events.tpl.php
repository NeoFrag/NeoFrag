<div role="tabpanel">
	<ul id="events-tabs" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#events-options" aria-controls="events-options" data-toggle="tab"><?php echo icon('fa-cogs'); ?> Options</a></li>
	</ul>
	<div class="tab-content">
		<div id="events-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-events" class="col-sm-3 control-label">Type</label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[type_id]" id="settings-events">
							<option value="0"<?php if ($data['type_id'] == 0) echo ' selected="selected"'; ?>>Tous</option>
							<?php foreach ($data['types'] as $type): ?>
								<option value="<?php echo $type['type_id']; ?>"<?php if ($data['type_id'] == $type['type_id']) echo ' selected="selected"'; ?>><?php echo $type['title']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>