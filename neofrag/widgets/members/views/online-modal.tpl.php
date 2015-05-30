<div class="modal fade" id="modal-online-{name}" tabindex="-1" role="dialog" aria-labelledby="modal-title-online-{name}" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modal-title-online-{name}">{title}</h4>
			</div>
			<div class="modal-body">
				<?php foreach ($data['users'] as $user): ?>
				<div class="media">
					<div class="media-left">
						<a href="{base_url}members/<?php echo $user['user_id']; ?>/<?php echo url_title($user['username']); ?>.html">
							<img src="<?php echo $NeoFrag->user->avatar($user['avatar'], $user['sex']); ?>" style="max-width: 40px; max-height: 40px;" alt="" />
						</a>
					</div>
					<div class="media-body">
						<h4 class="media-heading"><?php echo $NeoFrag->user->link($user['user_id'], $user['username']); ?></h4>
						{fa-icon clock-o} <?php echo time_span($user['last_activity']); ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>