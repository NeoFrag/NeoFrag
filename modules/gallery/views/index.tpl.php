<?php if (!empty($data['category_image'])): ?>
<img class="img-fluid" src="<?php echo path($data['category_image']) ?>" alt="" />
<?php endif ?>
<div class="panel-body">
	<?php if (!empty($data['gallery'])): ?>
	<div class="row">
		<?php foreach ($data['gallery'] as $album): ?>
			<div class="col-12">
				<h4><a href="<?php echo url('gallery/album/'.$album['gallery_id'].'/'.url_title($album['title'])) ?>"><?php echo $album['title'] ?></a></h4>
				<a href="<?php echo url('gallery/album/'.$album['gallery_id'].'/'.url_title($album['title'])) ?>" class="gallery-item thumbnail" style="background-image: url('<?php echo path(!empty($album['image']) ? $album['image'] : $this->db->select('file_id')->from('nf_gallery_images')->where('gallery_id', $album['gallery_id'])->order_by('RAND()')->limit(1)->row()) ?>');">
					<div class="black-caption-hover">
						<div class="black-caption-hover-content">
							<h4 class="no-margin"><?php echo icon('fa-eye').' '.$this->lang('Ouvrir l\'album') ?></h4>
						</div>
					</div>
					<div class="caption">
						<span class="badge badge-default"><?php echo icon('fa-photo').' '.$this->lang('<b>%d</b> image|<b>%d</b> images', $album['images'], $album['images']) ?></span>
					</div>
				</a>
			</div>
		<?php endforeach ?>
	</div>
	<?php else: ?>
	<div class="text-center"><?php echo $this->lang('Aucun album dans cette catégorie') ?></div>
	<?php endif ?>
</div>
