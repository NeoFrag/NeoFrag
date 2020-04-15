<div class="tab-content">
	<div class="tab-pane" data-tab="dashboard">
		<div class="row">
			<div class="col-12 col-lg-4">
				<img class="img-fluid img-thumbnail" src="<?php echo url($this->__caller->__path('', 'thumbnail.png')) ?>" alt="" />
			</div>
			<div class="col-12 col-lg-8">
				<dl class="row mt-2 mb-0">
					<dt class="col-sm-4 col-lg-3 text-truncate"><?php echo $this->lang('Nom du thème') ?></dt>
					<dd class="col-sm-8 col-lg-9"><?php echo $theme->title ?></dd>
					<dt class="col-sm-4 col-lg-3 text-truncate"><?php echo $this->lang('Description') ?></dt>
					<dd class="col-sm-8 col-lg-9"><?php echo $theme->description ?></dd>
					<dt class="col-sm-4 col-lg-3"><?php echo $this->lang('Version') ?></dt>
					<dd class="col-sm-8 col-lg-9"><?php echo $theme->version ?></dd>
					<dt class="col-sm-4 col-lg-3"><?php echo $this->lang('Auteurs') ?></dt>
					<dd class="col-sm-8 col-lg-9"><?php echo utf8_htmlentities($theme->author) ?></dd>
					<dt class="col-sm-4 col-lg-3"><?php echo $this->lang('Licence') ?></dt>
					<dd class="col-sm-8 col-lg-9"><a href="https://neofr.ag/license.html" target="_blank"><?php echo $theme->license ?></a></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="tab-pane" data-tab="background">
		<?php echo $form_background ?>
	</div>
	<div class="tab-pane" data-tab="colors">
		<?php echo $form_colors ?>
	</div>
</div>
