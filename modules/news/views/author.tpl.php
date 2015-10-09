<div class="media">
	<div class="media-left">
		<a href="<?php echo url('members/'.$data['user_id'].'/'.url_title($data['username']).'.html'); ?>">
			<img class="media-object" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" style="max-width: 40px; max-height: 40px;" alt="" />
		</a>
	</div>
	<div class="media-body">
		<div><?php echo $NeoFrag->user->link($data['user_id'], $data['username']); ?></div>
		<p>
			<small><?php echo icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.i18n($data['admin'] ? 'admin' : 'member').' '.i18n($data['online'] ? 'online' : 'offline'); ?></small>
		</p>
	</div>
</div>
<?php if (!empty($data['quote'])): ?>
<br />
<blockquote>
	<?php echo $data['quote']; ?>
</blockquote>
<?php endif; ?>