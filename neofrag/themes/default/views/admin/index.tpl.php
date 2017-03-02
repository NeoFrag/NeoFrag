<div class="tab-content">
	<div class="tab-pane" data-tab="dashboard">
		<div class="row">
			<div class="col-md-4">
				<img class="img-responsive thumbnail no-margin" src="<?php echo image('thumbnail.png'); ?>" alt="" />
			</div>
			<div class="col-md-8">
				<h2><?php echo $this->lang('theme_administration'); ?></h2>
				<dl class="dl-horizontal no-margin">
					<dt><?php echo $this->lang('theme_title'); ?></dt>
					<dd><?php echo $data['theme']->get_title(); ?></dd>
					<dt><?php echo $this->lang('description'); ?></dt>
					<dd><?php echo $data['theme']->lang($data['theme']->description, NULL); ?></dd>
					<dt><?php echo $this->lang('version'); ?></dt>
					<dd><?php echo $data['theme']->version; ?></dd>
					<dt><?php echo $this->lang('authors'); ?></dt>
					<dd><?php echo utf8_htmlentities($data['theme']->author); ?></dd>
					<dt><?php echo $this->lang('license'); ?></dt>
					<dd><a href="https://neofr.ag/license.html" target="_blank"><?php echo $data['theme']->licence; ?></a></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="tab-pane" data-tab="background">
		<?php echo $data['form_background']; ?>
	</div>
</div>