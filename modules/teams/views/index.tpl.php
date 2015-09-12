<?php if ($data['image_id']): ?>
<a href="<?php echo url('teams/'.$data['team_id'].'/'.$data['name'].'.html'); ?>"><img class="img-responsive" src="<?php echo path($data['image_id']); ?>" alt="" /></a>
<?php endif; ?>
<div class="panel-body">
	<?php if ($data['description']): ?>
	<h3>Présentation</h3>
	<?php echo $data['description']; ?>
	<?php endif; ?>
	<h3>Nos joueurs</h3>
	<?php echo $data['users']; ?>
</div>