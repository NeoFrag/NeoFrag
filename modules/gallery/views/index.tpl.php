<?php if (!empty($data['category_image'])): ?>
<img class="img-responsive" src="<?php echo path($data['category_image']); ?>" alt="" />
<?php endif; ?>
<div class="panel-body">
	<?php if (!empty($data['gallery'])): ?>
	<div class="row">
		<?php foreach ($data['gallery'] as $album): ?>
			<div class="col-md-12">
				<h4><a href="<?php echo url('gallery/album/'.$album['gallery_id'].'/'.url_title($album['title'])); ?>"><?php echo $album['title']; ?></a></h4>
				<a href="<?php echo url('gallery/album/'.$album['gallery_id'].'/'.url_title($album['title'])); ?>" class="gallery-item thumbnail" style="background-image: url('<?php echo path(!empty($album['image']) ? $album['image'] : $this->db->select('file_id')->from('nf_gallery_images')->where('gallery_id', $album['gallery_id'])->order_by('RAND()')->limit(1)->row()); ?>');">
					<div class="black-caption-hover">
						<div class="black-caption-hover-content">
							<h4 class="no-margin"><?php echo icon('fa-eye').' '.$this->lang('open_album'); ?></h4>
						</div>
					</div>
					<div class="caption">
						<span class="label label-default"><?php echo icon('fa-photo').' '.$this->lang('images', $album['images'], $album['images']); ?></span>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<?php else: ?>
	<div class="text-center"><?php echo $this->lang('no_category_albums'); ?></div>
	<?php endif; ?>
</div>
