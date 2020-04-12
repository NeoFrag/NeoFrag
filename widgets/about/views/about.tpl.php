<style type="text/css">
.about-content {
	padding-top: <?php echo $settings['padding_top'] ? $settings['padding_top'] : '0' ?>px;
	padding-right: <?php echo $settings['padding_right'] ? $settings['padding_right'] : '0' ?>px;
	padding-bottom: <?php echo $settings['padding_bottom'] ? $settings['padding_bottom'] : '0' ?>px;
	padding-left: <?php echo $settings['padding_left'] ? $settings['padding_left'] : '0' ?>px;
	margin-top: <?php echo $settings['margin_top'] ? $settings['margin_top'] : '0' ?>px;
	margin-right: <?php echo $settings['margin_right'] ? $settings['margin_right'] : '0' ?>px;
	margin-bottom: <?php echo $settings['margin_bottom'] ? $settings['margin_bottom'] : '0' ?>px;
	margin-left: <?php echo $settings['margin_left'] ? $settings['margin_left'] : '0' ?>px;
}
</style>
<div class="about-content">
	<?php if ($settings['display_teamname'] == 'oui'): ?>
		<?php if ($this->config->nf_team_name): ?><h3 class="<?php echo $settings['teamname_align'] ?>"<?php echo $settings['style_title'] ? ' style="color: '.$settings['style_title'].'"' : '' ?>><?php echo $this->config->nf_team_name ?></h3><?php endif ?>
		<?php if ($settings['display_type'] == 'oui' || $settings['display_date'] == 'oui'): ?>
		<ul class="list-inline <?php echo $settings['teamname_align'] ? $settings['teamname_align'] : 'text-left' ?>"<?php echo $settings['style_title'] ? ' style="color: '.$settings['style_title'].'"' : '' ?>>
			<?php if ($settings['display_type'] == 'oui' && $this->config->nf_team_type): ?><li class="list-inline-item"><?php echo icon('fas fa-university').' '.$this->config->nf_team_type ?></li><?php endif ?>
			<?php if ($settings['display_date'] == 'oui' && $this->config->nf_team_creation): ?><li class="list-inline-item"><?php echo icon('fas fa-calendar-alt').' '.timetostr('%e %b %Y', $this->config->nf_team_creation) ?></li><?php endif ?>
		</ul>
		<?php endif ?>
	<?php endif ?>
	<?php if ($settings['display_logo'] == 'oui' && $this->config->nf_team_logo): ?>
		<div class="<?php echo $settings['logo_align'] ?> mb-3">
			<img src="<?php echo NeoFrag()->model2('file', $this->config->nf_team_logo)->path() ?>" style="max-width: <?php echo $settings['logo_width'] ?>px;" alt="" />
		</div>
	<?php endif ?>
	<?php if ($settings['display_biographie'] == 'oui' && $this->config->nf_team_biographie): ?>
		<div class="<?php echo $settings['biographie_align'] ?>"<?php echo $settings['style_text'] ? ' style="color: '.$settings['style_text'].'"' : '' ?>>
			<?php echo bbcode($this->config->nf_team_biographie) ?>
		</div>
	<?php endif ?>
</div>
