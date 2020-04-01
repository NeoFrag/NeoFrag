<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Options' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
		<div class="form-group row">
			<label for="live-editor-settings-title" class="col-3 col-form-label"><?php echo $this->lang('Discussion') ?></label>
			<div class="col-5">
				<select class="form-control" name="settings[talk_id]">
				<?php foreach ($talks as $talk): ?>
					<option value="<?php echo $talk['talk_id'] ?>"<?php if (isset($settings['talk_id']) && $settings['talk_id'] == $talk['talk_id']) echo ' selected="selected"' ?>><?php echo $talk['name'] ?></option>
				<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
</div>
