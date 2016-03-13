<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="<?php echo $data['theme']->name; ?>-dashboard">
		<div class="row">
			<div class="col-md-4">
				<img class="img-responsive thumbnail no-margin" src="<?php echo image('thumbnail.png'); ?>" alt="" />
			</div>
			<div class="col-md-8">
				<h2><?php echo i18n('theme_administration'); ?></h2>
				<dl class="dl-horizontal no-margin">
					<dt><?php echo i18n('theme_title'); ?></dt>
					<dd><?php echo $data['theme']->get_title(); ?></dd>
					<dt><?php echo i18n('description'); ?></dt>
					<dd><?php echo $data['theme']->load->lang($data['theme']->description, NULL); ?></dd>
					<dt><?php echo i18n('version'); ?></dt>
					<dd><?php echo $data['theme']->version; ?></dd>
					<dt><?php echo i18n('authors'); ?></dt>
					<dd><?php echo $data['theme']->author; ?></dd>
					<dt><?php echo i18n('license'); ?></dt>
					<dd><a href="https://neofr.ag/license.html" target="_blank"><?php echo $data['theme']->licence; ?></a></dd>
				</dl>
			</div>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane" id="<?php echo $data['theme']->name; ?>-background">
		<?php echo $data['form_background']; ?>
	</div>
</div>