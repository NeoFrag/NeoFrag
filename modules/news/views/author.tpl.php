<div class="media">
	<div class="media-left">
		<a href="{base_url}members/{user_id}/{url_title(username)}.html">
			<img class="media-object" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" style="max-width: 40px; max-height: 40px;" alt="" />
		</a>
	</div>
	<div class="media-body">
		<div><?php echo $NeoFrag->user->link($data['user_id'], $data['username']); ?></div>
		<p>
			<small><i class="fa fa-circle <?php echo $data['online'] ? 'text-green' : 'text-gray'; ?>"></i> <?php echo $data['admin'] ? 'Admin' : 'Membre'; ?> <?php echo $data['online'] ? 'en ligne' : 'hors ligne'; ?></small>
		</p>
	</div>
</div>
<?php if (!empty($data['quote'])): ?>
<br />
<blockquote>
	<?php echo $data['quote']; ?>
</blockquote>
<?php endif; ?>