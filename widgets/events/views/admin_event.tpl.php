<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Options' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
		<div class="form-group row">
			<label for="settings-event" class="col-3 col-form-label">Événement à afficher</label>
			<div class="col-6">
				<select class="form-control" name="settings[event_id]" id="settings-event">
					<?php foreach ($events as $event): ?>
						<option value="<?php echo $event['event_id'] ?>"<?php if ($event_id == $event['event_id']) echo ' selected="selected"' ?>><?php echo $event['title'] ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
</div>
