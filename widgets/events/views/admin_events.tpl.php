<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Options' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
		<div class="form-group row">
			<label for="settings-events" class="col-3 col-form-label">Type</label>
			<div class="col-6">
				<select class="form-control" name="settings[type_id]" id="settings-events">
					<option value="0"<?php if ($type_id == 0) echo ' selected="selected"' ?>>Tous</option>
					<?php foreach ($types as $type): ?>
						<option value="<?php echo $type['type_id'] ?>"<?php if ($type_id == $type['type_id']) echo ' selected="selected"' ?>><?php echo $type['title'] ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
</div>
