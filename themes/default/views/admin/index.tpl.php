<div class="tab-content">
	<div class="tab-pane" data-tab="dashboard">
		<div class="row">
			<div class="col-4">
				<img class="img-fluid thumbnail m-0" src="<?php echo image('thumbnail.png') ?>" alt="" />
			</div>
			<div class="col-8">
				<h2><?php echo $this->lang('Administration du thème !') ?></h2>
				<dl class="dl-horizontal m-0">
					<dt><?php echo $this->lang('Nom du thème') ?></dt>
					<dd><?php echo $theme->get_title() ?></dd>
					<dt><?php echo $this->lang('Description') ?></dt>
					<dd><?php echo $theme->description ?></dd>
					<dt><?php echo $this->lang('Version') ?></dt>
					<dd><?php echo $theme->version ?></dd>
					<dt><?php echo $this->lang('Auteurs') ?></dt>
					<dd><?php echo utf8_htmlentities($theme->author) ?></dd>
					<dt><?php echo $this->lang('Licence') ?></dt>
					<dd><a href="https://neofr.ag/license.html" target="_blank"><?php echo $theme->licence ?></a></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="tab-pane" data-tab="background">
		<?php echo $form_background ?>
	</div>
</div>
