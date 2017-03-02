<div role="tabpanel">
	<ul id="navigation-tabs" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#navigation-options" aria-controls="navigation-options" role="tab" data-toggle="tab"><?php echo icon('fa-cogs').' '.$this->lang('options'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div id="navigation-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-gallery" class="col-sm-3 control-label"><?php echo $this->lang('random_picture'); ?></label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[gallery_id]" id="settings-gallery">
							<option value="0"<?php if ($data['gallery_id'] == 0) echo ' selected="selected"'; ?>><?php $this->lang('all_galleries'); ?></option>
							<?php foreach ($data['gallery'] as $gallery): ?>
								<option value="<?php echo $gallery['gallery_id']; ?>"<?php if ($data['gallery_id'] == $gallery['gallery_id']) echo ' selected="selected"'; ?>><?php echo $gallery['title']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>