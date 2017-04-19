<?php if ($image_id): ?>
<a href="<?php echo url('teams/'.$team_id.'/'.$name) ?>"><img class="img-responsive" src="<?php echo $this->model2('file', $image_id)->path() ?>" alt="" /></a>
<?php endif ?>
<div class="panel-body">
	<?php if ($description): ?>
	<h3><?php echo $this->lang('overview') ?></h3>
	<?php echo $description ?>
	<?php endif ?>
	<h3><?php echo $this->lang('our_players') ?></h3>
	<?php echo $users ?>
</div>
