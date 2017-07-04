<?php if ($image_id): ?>
<a href="<?php echo url('teams/'.$team_id.'/'.$name) ?>"><img class="img-fluid" src="<?php echo path($image_id) ?>" alt="" /></a>
<?php endif ?>
<div class="panel-body">
	<?php if ($description): ?>
	<h3><?php echo $this->lang('Présentation') ?></h3>
	<?php echo $description ?>
	<?php endif ?>
	<h3><?php echo $this->lang('Nos joueurs') ?></h3>
	<?php echo $users ?>
</div>
