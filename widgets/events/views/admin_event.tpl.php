<div role="tabpanel">
	<ul id="events-tabs" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#events-options" aria-controls="events-options" data-toggle="tab"><?php echo icon('fa-cogs'); ?> Options</a></li>
	</ul>
	<div class="tab-content">
		<div id="events-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-events" class="col-sm-3 control-label">Événement à afficher</label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[event_id]" id="settings-events">
							<?php foreach ($data['events'] as $event): ?>
								<option value="<?php echo $event['event_id']; ?>"<?php if ($data['event_id'] == $event['event_id']) echo ' selected="selected"'; ?>><?php echo $event['title']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>