<?php foreach ($teams as $team): ?>
<div class="roster">
	<span class="roster-name"><a href="<?php echo url('teams/'.$team['team_id'].'/'.$team['name']) ?>"><?php echo $team['title'] ?></a></span>
	<a href="<?php echo url('teams/'.$team['team_id'].'/'.$team['name']) ?>"><img src="<?php echo NeoFrag()->model2('file', $team['image_id'])->path() ?>" class="img-fluid" alt="" /></a>
</div>
<?php endforeach ?>
