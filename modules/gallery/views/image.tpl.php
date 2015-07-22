<img class="img-responsive" src="{image {file_id}}" alt="" />
<?php if (!empty($data['vignettes'])): ?>
<div class="panel-body">
	<div class="row">
		<?php foreach ($data['vignettes'] as $vignette): ?>
		<div class="col-md-2">
			<a class="thumbnail<?php echo ($data['image_id'] == $vignette['image_id']) ? ' active' : '' ; ?>" href="<?php echo $this->config->base_url.'gallery/image/'.$vignette['image_id'].'/'.url_title($vignette['title']); ?>.html">
				<img class="img-responsive" src="{image <?php echo $vignette['thumbnail_file_id']; ?>}" alt="" />
			</a>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>