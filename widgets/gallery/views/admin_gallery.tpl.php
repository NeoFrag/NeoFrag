<div role="tabpanel">
	<ul id="navigation-tabs" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#navigation-options" aria-controls="navigation-options" role="tab" data-toggle="tab"><?php echo icon('fa-cogs').' '.$this->lang('options'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div id="navigation-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-category" class="col-sm-3 control-label"><?php echo $this->lang('gallery_to_display'); ?></label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[category_id]" id="settings-category">
							<?php foreach ($data['categories'] as $category): ?>
								<option value="<?php echo $category['category_id']; ?>"<?php if ($data['category_id'] == $category['category_id']) echo ' selected="selected"'; ?>><?php echo $category['title']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>