<?php foreach ($data['teams'] as $team): ?>
<div class="roster">
	<span class="roster-name"><a href="<?php echo url('teams/'.$team['team_id'].'/'.$team['name'].'.html'); ?>"><?php echo $team['title']; ?></a></span>
	<a href="<?php echo url('teams/'.$team['team_id'].'/'.$team['name'].'.html'); ?>"><img src="<?php echo path($team['image_id']); ?>" class="img-responsive" alt="" /></a>
</div>
<?php endforeach; ?>