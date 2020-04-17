<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Contenu' ?></a></li>
	<li class="nav-item"><a class="nav-link" id="pills-display-tab" data-toggle="pill" href="#pills-display" role="tab" aria-controls="pills-display" aria-selected="false"><?php echo icon('fas fa-desktop').' Affichage' ?></a></li>
	<li class="nav-item"><a class="nav-link" id="pills-style-tab" data-toggle="pill" href="#pills-style" role="tab" aria-controls="pills-style" aria-selected="false"><?php echo icon('fas fa-paint-brush').' Style' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
		<div class="form-group row">
			<label for="settings-display_teamname" class="col-3 col-form-label">Nom de l'équipe</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_teamname]" id="settings-display_teamname">
					<option value="non"<?php if (!isset($display_teamname) || $display_teamname == 'non') echo ' selected="selected"' ?>>Non</option>
					<option value="oui"<?php if (isset($display_teamname) && $display_teamname == 'oui') echo ' selected="selected"' ?>>Oui</option>
				</select>
			</div>
			<div class="col-3">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-light<?php if ($teamname_align == 'text-left') echo ' active' ?>">
						<input type="radio" name="settings[teamname_align]" value="text-left"<?php if ($teamname_align == 'text-left') echo ' checked' ?>><?php echo icon('fas fa-align-left') ?>
					</label>
					<label class="btn btn-light<?php if ($teamname_align == 'text-center') echo ' active' ?>">
						<input type="radio" name="settings[teamname_align]" value="text-center"<?php if ($teamname_align == 'text-center') echo ' checked' ?>><?php echo icon('fas fa-align-center') ?>
					</label>
					<label class="btn btn-light<?php if ($teamname_align == 'text-right') echo ' active' ?>">
						<input type="radio" name="settings[teamname_align]" value="text-right"<?php if ($teamname_align == 'text-right') echo ' checked' ?>><?php echo icon('fas fa-align-right') ?>
					</label>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-display_logo" class="col-3 col-form-label">Logo de l'équipe</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_logo]" id="settings-display_logo">
					<option value="non"<?php if (!isset($display_logo) || $display_logo == 'non') echo ' selected="selected"' ?>>Non</option>
					<option value="oui"<?php if (isset($display_logo) && $display_logo == 'oui') echo ' selected="selected"' ?>>Oui</option>
				</select>
			</div>
			<div class="col-3">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-light<?php if ($logo_align == 'text-left') echo ' active' ?>">
						<input type="radio" name="settings[logo_align]" value="text-left"<?php if ($logo_align == 'text-left') echo ' checked' ?>><?php echo icon('fas fa-align-left') ?>
					</label>
					<label class="btn btn-light<?php if ($logo_align == 'text-center') echo ' active' ?>">
						<input type="radio" name="settings[logo_align]" value="text-center"<?php if ($logo_align == 'text-center') echo ' checked' ?>><?php echo icon('fas fa-align-center') ?>
					</label>
					<label class="btn btn-light<?php if ($logo_align == 'text-right') echo ' active' ?>">
						<input type="radio" name="settings[logo_align]" value="text-right"<?php if ($logo_align == 'text-right') echo ' checked' ?>><?php echo icon('fas fa-align-right') ?>
					</label>
				</div>
			</div>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('fas fa-arrows-alt-h') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[logo_width]" value="<?php echo $logo_width ? $logo_width : '200' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-display_type" class="col-3 col-form-label">Type de structure</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_type]" id="settings-display_type">
					<option value="non"<?php if (!isset($display_type) || $display_type == 'non') echo ' selected="selected"' ?>>Non</option>
					<option value="oui"<?php if (isset($display_type) && $display_type == 'oui') echo ' selected="selected"' ?>>Oui</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-display_date" class="col-3 col-form-label">Date de création</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_date]" id="settings-display_date">
					<option value="non"<?php if (!isset($display_date) || $display_date == 'non') echo ' selected="selected"' ?>>Non</option>
					<option value="oui"<?php if (isset($display_date) && $display_date == 'oui') echo ' selected="selected"' ?>>Oui</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-display_biographie" class="col-3 col-form-label">Biographie</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_biographie]" id="settings-display_biographie">
					<option value="non"<?php if (!isset($display_biographie) || $display_biographie == 'non') echo ' selected="selected"' ?>>Non</option>
					<option value="oui"<?php if (isset($display_biographie) && $display_biographie == 'oui') echo ' selected="selected"' ?>>Oui</option>
				</select>
			</div>
			<div class="col-3">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-light<?php if ($biographie_align == 'text-left') echo ' active' ?>">
						<input type="radio" name="settings[biographie_align]" value="text-left"<?php if ($biographie_align == 'text-left') echo ' checked' ?>><?php echo icon('fas fa-align-left') ?>
					</label>
					<label class="btn btn-light<?php if ($biographie_align == 'text-center') echo ' active' ?>">
						<input type="radio" name="settings[biographie_align]" value="text-center"<?php if ($biographie_align == 'text-center') echo ' checked' ?>><?php echo icon('fas fa-align-center') ?>
					</label>
					<label class="btn btn-light<?php if ($biographie_align == 'text-right') echo ' active' ?>">
						<input type="radio" name="settings[biographie_align]" value="text-right"<?php if ($biographie_align == 'text-right') echo ' checked' ?>><?php echo icon('fas fa-align-right') ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="pills-display" role="tabpanel" aria-labelledby="pills-display-tab">
		<div class="form-group row">
			<label for="settings-display_panel" class="col-3 col-form-label">Dans un panel</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_panel]" id="settings-display_panel">
					<option value="non"<?php if (!isset($display_panel) || $display_panel == 'non') echo ' selected="selected"' ?>>Non</option>
					<option value="oui"<?php if (isset($display_panel) && $display_panel == 'oui') echo ' selected="selected"' ?>>Oui</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-margin_top" class="col-3 col-form-label">Margin top</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('fas fa-caret-up') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[margin_top]" value="<?php echo $margin_top ? $margin_top : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-margin_right" class="col-3 col-form-label">Margin right</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('fas fa-caret-right') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[margin_right]" value="<?php echo $margin_right ? $margin_right : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-margin_bottom" class="col-3 col-form-label">Margin bottom</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('fas fa-caret-down') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[margin_bottom]" value="<?php echo $margin_bottom ? $margin_bottom : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-margin_left" class="col-3 col-form-label">Margin left</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('fas fa-caret-left') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[margin_left]" value="<?php echo $margin_left ? $margin_left : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-padding_top" class="col-3 col-form-label">Padding top</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('far fa-caret-square-up') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[padding_top]" value="<?php echo $padding_top ? $padding_top : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-padding_right" class="col-3 col-form-label">Padding right</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('far fa-caret-square-right') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[padding_right]" value="<?php echo $padding_right ? $padding_right : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-padding_bottom" class="col-3 col-form-label">Padding bottom</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('far fa-caret-square-down') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[padding_bottom]" value="<?php echo $padding_bottom ? $padding_bottom : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-padding_left" class="col-3 col-form-label">Padding left</label>
			<div class="col-4">
				<div class="form-group mb-0">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?php echo icon('far fa-caret-square-left') ?></div>
						</div>
						<input type="number" class="form-control" name="settings[padding_left]" value="<?php echo $padding_left ? $padding_left : '0' ?>">
						<div class="input-group-append">
							<div class="input-group-text">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="pills-style" role="tabpanel" aria-labelledby="pills-style-tab">
		<div class="form-group row">
			<label for="settings-style_title" class="col-3 col-form-label">Couleur des titres</label>
			<div class="col-4">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><?php echo icon('fas fa-paint-brush') ?></div>
					</div>
					<input type="text" class="form-control" name="settings[style_title]" value="<?php echo $style_title ?>" placeholder="#000000..." />
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-style_text" class="col-3 col-form-label">Couleur des textes</label>
			<div class="col-4">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><?php echo icon('fas fa-paint-brush') ?></div>
					</div>
					<input type="text" class="form-control" name="settings[style_text]" value="<?php echo $style_text ?>" placeholder="#000000..." />
				</div>
			</div>
		</div>
	</div>
</div>
