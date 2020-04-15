<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Disposition' ?></a></li>
	<li class="nav-item"><a class="nav-link" id="pills-style-tab" data-toggle="pill" href="#pills-style" role="tab" aria-controls="pills-style" aria-selected="false"><?php echo icon('fas fa-paint-brush').' Style' ?></a></li>
	<li class="nav-item"><a class="nav-link" id="pills-display-tab" data-toggle="pill" href="#pills-display" role="tab" aria-controls="pills-display" aria-selected="false"><?php echo icon('fas fa-desktop').' Affichage' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
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
			<label for="settings-social_display" class="col-3 col-form-label">Disposition</label>
			<div class="col-9">
				<select class="form-control" name="settings[social_display]" id="settings-display_teamname">
					<option value="col-12"<?php if (!isset($social_display) || $social_display == 'col-12') echo ' selected="selected"' ?>>1 bouton par ligne</option>
					<option value="col-6"<?php if (isset($social_display) && $social_display == 'col-6') echo ' selected="selected"' ?>>2 boutons par ligne</option>
					<option value="col-4"<?php if (isset($social_display) && $social_display == 'col-4') echo ' selected="selected"' ?>>3 boutons par ligne</option>
					<option value="col-3"<?php if (isset($social_display) && $social_display == 'col-3') echo ' selected="selected"' ?>>4 boutons par ligne</option>
					<option value="col-2"<?php if (isset($social_display) && $social_display == 'col-2') echo ' selected="selected"' ?>>6 boutons par ligne</option>
					<option value="col-1"<?php if (isset($social_display) && $social_display == 'col-1') echo ' selected="selected"' ?>>12 boutons par ligne</option>
					<option value="col"<?php if (isset($social_display) && $social_display == 'col') echo ' selected="selected"' ?>>Répartition automatique</option>
					<option value="ul-inline"<?php if (isset($social_display) && $social_display == 'ul-inline') echo ' selected="selected"' ?>>Liste horizontale</option>
					<option value="ul"<?php if (isset($social_display) && $social_display == 'ul') echo ' selected="selected"' ?>>Liste verticale</option>
				</select>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="pills-style" role="tabpanel" aria-labelledby="pills-style-tab">
		<div class="form-group row">
			<label for="settings-social_style" class="col-3 col-form-label">Apparence</label>
			<div class="col-6">
				<select class="form-control" name="settings[social_style]" id="settings-social_style">
					<option value="btn btn-social"<?php if (!isset($social_style) || $social_style == 'btn btn-social') echo ' selected="selected"' ?>>Bouton normal</option>
					<option value="btn btn-social btn-sm"<?php if (isset($social_style) && $social_style == 'btn btn-social btn-sm') echo ' selected="selected"' ?>>Petit bouton</option>
					<option value="btn btn-social btn-lg"<?php if (isset($social_style) && $social_style == 'btn btn-social btn-lg') echo ' selected="selected"' ?>>Grand bouton</option>
					<option value="btn btn-link"<?php if (isset($social_style) && $social_style == 'btn btn-link') echo ' selected="selected"' ?>>Simple lien</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-content_display" class="col-3 col-form-label">Contenu</label>
			<div class="col-6">
				<select class="form-control" name="settings[content_display]" id="settings-content_display">
					<option value="all"<?php if (!isset($content_display) || $content_display == 'all') echo ' selected="selected"' ?>>Icône et légende</option>
					<option value="icon"<?php if (isset($content_display) && $content_display == 'icon') echo ' selected="selected"' ?>>Icône seule</option>
					<option value="legend"<?php if (isset($content_display) && $content_display == 'legend') echo ' selected="selected"' ?>>Légende seule</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-icon_size" class="col-3 col-form-label">Taille de l'icône</label>
			<div class="col-3">
				<select class="form-control" name="settings[icon_size]" id="settings-icon_size">
					<option value="fa-1x"<?php if (!isset($icon_size) || $icon_size == 'fa-1x') echo ' selected="selected"' ?>>Par défaut</option>
					<option value="fa-2x"<?php if (isset($icon_size) && $icon_size == 'fa-2x') echo ' selected="selected"' ?>>Grande</option>
					<option value="fa-3x"<?php if (isset($icon_size) && $icon_size == 'fa-3x') echo ' selected="selected"' ?>>Très grande</option>
					<option value="fa-4x"<?php if (isset($icon_size) && $icon_size == 'fa-4x') echo ' selected="selected"' ?>>Enorme !</option>
				</select>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="pills-display" role="tabpanel" aria-labelledby="pills-display-tab">
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
</div>
