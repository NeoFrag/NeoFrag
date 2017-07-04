<?php foreach ($topics as $topic): ?>
<div class="media">
	<?php if ($topic['user_id']): ?>
		<?php echo $this->user->avatar($topic['avatar'], $topic['sex'], $topic['user_id'], $topic['username']) ?>
	<?php else: ?>
		<?php echo $this->user->avatar(NULL) ?>"
	<?php endif ?>
</div>
<?php endforeach ?>
