<div role="tabpanel tab-upload">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#multiple" aria-controls="multiple" role="tab" data-toggle="tab"><?php echo $this->lang('multiple'); ?></a></li>
		<li role="presentation"><a href="#single" aria-controls="single" role="tab" data-toggle="tab"><?php echo $this->lang('simple'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="multiple">
			<div class="upload-infos">
				<span class="progress-size pull-right"></span>
				<span class="progress-percent"></span>
				<div class="progress">
					<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 10%;"></div>
				</div>
			</div>
			<form action="<?php echo url('admin/ajax/gallery/image/add/'.$data['gallery_id'].'/'.$data['name']); ?>" method="post" class="dropzone" id="gallery-dropzone" role="form">
				<div class="pull-right label-dropzone">
					<span class="label label-info" style="padding: 4px 5px;" data-toggle="tooltip" title="<?php echo $this->lang('click_select_images'); ?>">&nbsp;<?php echo icon('fa-info'); ?>&nbsp;</span>
				</div>
			</form>
			<button type="button" class="btn btn-primary btn-lg btn-block" id="gallery-dropzone-add"><?php echo icon('fa-cloud-upload').' '.$this->lang('dropzone_upload_btn'); ?></button>
		</div>
		<div role="tabpanel" class="tab-pane" id="single">
			<?php echo $data['form_image']; ?>
		</div>
	</div>
</div>