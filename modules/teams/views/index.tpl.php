<?php if ($data['image_id']): ?>
<a href="<?php echo url('teams/'.$data['team_id'].'/'.$data['name']) ?>"><img class="img-fluid" src="<?php echo path($data['image_id']) ?>" alt="" /></a>
<?php endif ?>
<div class="panel-body">
	<?php if ($data['description']): ?>
	<h3><?php echo $this->lang('Présentation') ?></h3>
	<?php echo $data['description'] ?>
	<?php endif ?>
	<h3><?php echo $this->lang('Nos joueurs') ?></h3>
	<?php echo $data['users'] ?>
</div>
