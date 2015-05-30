<ul class="list-group">
	<?php foreach ($data['members'] as $member): ?>
	<li class="list-group-item">
		<span class="pull-right"><i class="fa fa-clock-o"></i> <?php echo time_span($member['registration_date']); ?></span>
		<?php echo $NeoFrag->user->link($member['user_id'], $member['username']); ?>
	</li>
	<?php endforeach; ?>
</ul>