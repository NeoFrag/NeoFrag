<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="<?php echo $data['theme']->get_name(); ?>-dashboard">
		<div class="row">
			<div class="col-md-4">
				<img class="img-responsive thumbnail no-margin" src="{image thumbnail.png}" alt="" />
			</div>
			<div class="col-md-8">
				<h2>Administration du thème !</h2>
				<dl class="dl-horizontal no-margin">
					<dt>Nom du thème</dt>
					<dd><?php echo $data['theme']->name; ?></dd>
					<dt>Description</dt>
					<dd><?php echo $data['theme']->description; ?></dd>
					<dt>Version</dt>
					<dd><?php echo $data['theme']->version; ?></dd>
					<dt>Auteurs</dt>
					<dd><?php echo $data['theme']->author; ?></dd>
					<dt>License</dt>
					<dd><a href="http://www.neofrag.fr/license.html" target="_blank"><?php echo $data['theme']->licence; ?></a></dd>
				</dl>
			</div>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane" id="<?php echo $data['theme']->get_name(); ?>-background">
		{form_background}
	</div>
</div>