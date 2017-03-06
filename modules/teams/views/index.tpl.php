<?php if ($data['image_id']): ?>
<a href="<?php echo url('teams/'.$data['team_id'].'/'.$data['name']); ?>"><img class="img-responsive" src="<?php echo path($data['image_id']); ?>" alt="" /></a>
<?php endif; ?>
<div class="panel-body">
	<?php if ($data['description']): ?>
	<h3><?php echo $this->lang('overview'); ?></h3>
	<?php echo $data['description']; ?>
	<?php endif; ?>
	<h3><?php echo $this->lang('our_players'); ?></h3>
	<?php echo $data['users']; ?>
</div>