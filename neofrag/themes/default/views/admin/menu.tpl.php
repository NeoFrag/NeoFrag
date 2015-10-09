<ul class="nav nav-pills nav-stacked nav-<?php echo $data['theme_name']; ?>">
	<li class="active"><a href="#<?php echo $data['theme_name']; ?>-dashboard" aria-controls="<?php echo $data['theme_name']; ?>-dashboard" role="tab" data-toggle="tab"><?php echo icon('fa-cog').' '.i18n('dashboard'); ?></a></li>
	<li><a href="#<?php echo $data['theme_name']; ?>-background" aria-controls="<?php echo $data['theme_name']; ?>-background" role="tab" data-toggle="tab"><?php echo icon('fa-image').' '.i18n('website_background'); ?></a></li>
</ul>