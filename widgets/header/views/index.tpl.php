<div class="<?php echo $align ?>">
	<h2 class="site-title"<?php if (!empty(${'color-title'})) echo 'style="color: '.${'color-title'}.'"' ?>><?php echo $title ?: $this->config->nf_name ?></h2>
	<h5 class="site-description"<?php if (!empty(${'color-description'})) echo ' style="color: '.${'color-description'}.'"' ?>><?php echo $description ?: $this->config->nf_description ?></h5>
</div>
