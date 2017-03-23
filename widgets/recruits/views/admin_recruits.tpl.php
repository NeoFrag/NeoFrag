<div role="tabpanel">
	<ul id="recruits-tabs" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#recruits-options" aria-controls="recruits-options" data-toggle="tab"><?php echo icon('fa-cogs'); ?> Options</a></li>
	</ul>
	<div class="tab-content">
		<div id="recruits-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-recruits" class="col-sm-3 control-label">Offre à afficher</label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[recruit_id]" id="settings-recruits">
							<?php foreach ($data['recruits'] as $recruit): ?>
								<option value="<?php echo $recruit['recruit_id']; ?>"<?php if ($data['recruit_id'] == $recruit['recruit_id']) echo ' selected="selected"'; ?>><?php echo $recruit['title']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>