<ul class="list-group">
	<?php foreach ($data['members'] as $member): ?>
	<li class="list-group-item">
		<span class="pull-right"><?php echo icon('fa-clock-o').' '.time_span($member['registration_date']); ?></span>
		<?php echo $this->user->link($member['user_id'], $member['username']); ?>
	</li>
	<?php endforeach; ?>
</ul>