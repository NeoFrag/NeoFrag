<div role="tabpanel tab-upload">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#multiple" aria-controls="multiple" role="tab" data-toggle="tab"><?php echo $this->lang('Multiple') ?></a></li>
		<li role="presentation"><a href="#single" aria-controls="single" role="tab" data-toggle="tab"><?php echo $this->lang('Simple') ?></a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="multiple">
			<div class="upload-infos">
				<span class="progress-size float-right"></span>
				<span class="progress-percent"></span>
				<div class="progress">
					<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 10%;"></div>
				</div>
			</div>
			<form action="<?php echo url('admin/ajax/gallery/image/add/'.$gallery_id.'/'.$name) ?>" method="post" class="dropzone" id="gallery-dropzone" role="form"></form>
			<button type="button" class="btn btn-primary btn-lg btn-block" id="gallery-dropzone-add"><?php echo icon('fas fa-cloud-upload-alt').' '.$this->lang('Ajouter les images') ?></button>
		</div>
		<div role="tabpanel" class="tab-pane" id="single">
			<?php echo $form_image ?>
		</div>
	</div>
</div>
