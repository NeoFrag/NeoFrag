<?php if (!empty($data['category_image'])): ?>
<img class="img-responsive" src="{image <?php echo $data['category_image']; ?>}" alt="" />
<?php endif; ?>
<div class="panel-body">
	<?php if (!empty($data['gallery'])): ?>
	<div class="row">
		<?php foreach ($data['gallery'] as $album): ?>
			<div class="col-md-12">
				<h4><a href="<?php echo $this->config->base_url.'gallery/album/'.$album['gallery_id'].'/'.url_title($album['title']); ?>.html"><?php echo $album['title']; ?></a></h4>
				<a href="<?php echo $this->config->base_url.'gallery/album/'.$album['gallery_id'].'/'.url_title($album['title']); ?>.html" class="gallery-item thumbnail" style="background-image: url('{image <?php echo !empty($album['image']) ? $album['image'] : $this->db->select('file_id')->from('nf_gallery_images')->where('gallery_id', $album['gallery_id'])->order_by('RAND()')->limit(1)->row(); ?>}');">
					<div class="black-caption-hover">
						<div class="black-caption-hover-content">
							<h4 class="no-margin"><i class="fa fa-eye"></i> Ouvrir l'album</h4>
						</div>
					</div>
					<div class="caption">
						<span class="label label-default">{fa-icon photo} <?php echo $album['images']; ?> <?php echo $album['images'] > 1 ? 'images' : 'image'; ?></span>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<?php else: ?>
	<div class="text-center">Aucun album dans cette catégorie</div>
	<?php endif; ?>
</div>