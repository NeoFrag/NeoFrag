<div class="forum-profile">
	<?php if (!empty($data['user_id'])): ?>
	<h4><?php echo $NeoFrag->user->link($data['user_id'], $data['username']);?></h4>
	<p><?php echo icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.i18n($data['admin'] ? 'admin' : 'member').' '.i18n($data['online'] ? 'online' : 'offline'); ?></p>
	<img class="img-avatar-forum" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" title="<?php echo $data['username']; ?>" alt="" />
	<p><i><?php echo i18n('topics', $data['topics'], $data['topics']).' '.i18n('messages', $data['replies'], $data['replies']); ?></i></p>
	<div class="forum-groups">
		<?php echo $NeoFrag->groups->user_groups($data['user_id']); ?>
	</div>
	<?php else: ?>
	<h4><i><?php echo i18n('guest'); ?></i></h4>
	<img class="img-avatar-forum" src="<?php echo $NeoFrag->user->avatar(NULL); ?>" alt="" />
	<?php endif; ?>
</div>
