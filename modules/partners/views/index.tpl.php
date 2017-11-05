<?php
$count = count($data['partners']);
foreach ($data['partners'] as $i => $partner): ?>
	<div class="row">
		<div class="col-md-5 text-center" style="padding-right: 15px;">
			<a href="<?php echo url('partners/'.$partner['partner_id'].'/'.$partner['name']) ?>" target="_blank" class="thumbnail" style="padding: 10px;">
				<?php if ($partner[$this->config->partners_logo_display]): ?>
				<img src="<?php echo path($partner[$this->config->partners_logo_display]) ?>" class="img-responsive" alt="" />
				<?php else: ?>
				<h3 class="no-margin"><?php echo $partner['title'] ?></h3>
				<?php endif ?>
			</a>
		</div>
		<div class="col-md-7">
			<div class="pull-right"><a class="text-muted" href="<?php echo url('partners/'.$partner['partner_id'].'/'.$partner['name']) ?>" target="_blank"><?php echo preg_replace('_https?://_', '', $partner['website']) ?></a></div>
			<h4>À propos de <b><?php echo $partner['title'] ?></b></h4>
			<ul class="list-inline">
				<?php if ($partner['facebook']) echo '<li><a href="'.$partner['facebook'].'" class="btn-primary" target="_blank" data-toggle="tooltip" title="Facebook">'.icon('fa-facebook').'</a></li>' ?>
				<?php if ($partner['twitter']) echo '<li><a href="'.$partner['twitter'].'" class="btn-info" target="_blank" data-toggle="tooltip" title="Twitter">'.icon('fa-twitter').'</a></li>' ?>
				<?php if ($partner['code']) echo '<li><span class="btn-link" data-toggle="tooltip" title="Code promotionnel">'.icon('fa-gift').'</span> '.$partner['code'].'</li>' ?>
			</ul>
			<?php if ($partner['description']) echo '<p>'.bbcode($partner['description']).'</p>' ?>
		</div>
	</div>
<?php
	if ($i < $count - 1) echo '<hr />';
endforeach;
?>
