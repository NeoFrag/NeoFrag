<div class="media">

	<?php echo $this->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']) ?>

	<div class="media-body">
		<div><?php echo $this->user->link($data['user_id'], $data['username']) ?></div>
		<p>
			<small><?php echo icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$this->lang($data['admin'] ? 'Administrateur' : 'Membre').' '.$this->lang($data['online'] ? 'en ligne' : 'hors ligne') ?></small>
		</p>
	</div>
</div>
<?php if (!empty($data['quote'])): ?>
<br />
<blockquote>
	<?php echo $data['quote'] ?>
</blockquote>
<?php endif ?>
