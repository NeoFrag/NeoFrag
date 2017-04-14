<div role="tabpanel">
	<ul id="navigation-tabs" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#navigation-options" aria-controls="navigation-options" role="tab" data-toggle="tab"><?php echo icon('fa-cogs').' '.$this->lang('Options') ?></a></li>
	</ul>
	<div class="tab-content">
		<div id="navigation-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-align" class="col-sm-3 control-label"><?php echo $this->lang('Alignement') ?></label>
					<div class="col-sm-3">
						<select class="form-control" name="settings[align]" id="settings-align">
							<option value="text-left"<?php if (isset($align) && $align == 'text-left') echo ' selected="selected"' ?>><?php echo $this->lang('Gauche') ?></option>
							<option value="text-center"<?php if (!isset($align) || $align == 'text-center') echo ' selected="selected"' ?>><?php echo $this->lang('Centré') ?></option>
							<option value="text-right"<?php if (isset($align) && $align == 'text-right') echo ' selected="selected"' ?>><?php echo $this->lang('Droite') ?></option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-title" class="col-sm-3 control-label"><?php echo $this->lang('Titre du site') ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="settings[title]" value="<?php if (isset($title)) echo $title ?>" id="settings-title" placeholder="<?php echo $this->lang('Titre par défaut') ?>" />
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><?php echo icon('fa-paint-brush') ?></span>
							</div>
							<input type="text" class="form-control" name="settings[color-title]" value="<?php if (isset(${'color-title'})) echo ${'color-title'} ?>" placeholder="#000000" /><!-- //TODO color picker -->
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-description" class="col-sm-3 control-label"><?php echo $this->lang('Description') ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="settings[description]" value="<?php if (isset($description)) echo $description ?>" id="settings-description" placeholder="<?php echo $this->lang('Description par défaut') ?>" />
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><?php echo icon('fa-paint-brush') ?></span>
							</div>
							<input type="text" class="form-control" name="settings[color-description]" value="<?php if (isset(${'color-description'})) echo ${'color-description'} ?>" placeholder="#000000" /><!-- //TODO color picker -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
