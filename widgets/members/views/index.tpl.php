<ul class="list-group list-group-flush">
	<?php foreach ($members as $member): ?>
	<li class="list-group-item">
		<span class="float-right"><?php echo icon('far fa-clock').' '.timetostr('%e %b %Y', $member['registration_date']) ?></span>
		<?php echo $this->user->link($member['user_id'], $member['username']) ?>
	</li>
	<?php endforeach ?>
</ul>
