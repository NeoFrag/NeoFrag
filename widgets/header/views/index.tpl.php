<div class="<?php echo $align ?>">
	<?php if (isset($display) && $display == 'logo' && !empty($this->config->nf_team_logo)): ?>
		<a href="<?php echo url() ?>"><img src="<?php echo NeoFrag()->model2('file', $this->config->nf_team_logo)->path() ?>" class="header-logo" alt="" /></a>
	<?php else: ?>
		<h2 class="site-title"<?php if (!empty($color_title)) echo ' style="color: '.$color_title.'"' ?>><?php echo $title ?: $this->config->nf_name ?></h2>
		<h5 class="site-description"<?php if (!empty($color_description)) echo ' style="color: '.$color_description.'"' ?>><?php echo $description ?: $this->config->nf_description ?></h5>
	<?php endif ?>
</div>
