<div class="card col-lg-1 col-md-2 col-sm-2 px-0">
	<div class="card-body p-3">
		<p><a href="<?php echo url('user/messages/compose') ?>" data-toggle="tooltip" title="<?php echo $this->lang('Nouveau message') ?>" class="btn btn-primary btn-block"><?php echo icon('far fa-envelope') ?></a></p>
		<div class="btn-group-vertical btn-block">
			<a href="<?php echo url('user/messages') ?>" data-toggle="tooltip" title="<?php echo $this->lang('Boîte de réception') ?>" class="btn btn-<?php echo $box == 'inbox' ? 'secondary' : 'light' ?>"><?php echo icon('fas fa-inbox') ?></a>
			<a href="<?php echo url('user/messages/sent') ?>" data-toggle="tooltip" title="<?php echo $this->lang('Messages envoyé') ?>" class="btn btn-<?php echo $box == 'sent' ? 'secondary' : 'light' ?>"><?php echo icon('far fa-paper-plane') ?></a>
			<a href="<?php echo url('user/messages/archives') ?>" data-toggle="tooltip" title="<?php echo $this->lang('Archives') ?>" class="btn btn-<?php echo $box == 'archives' ? 'secondary' : 'light' ?>"><?php echo icon('fas fa-archive') ?></a>
		</div>
	</div>
</div>
<div class="card col-lg-4 px-0">
	<div class="card-body">
		<h5><?php echo icon($page_icon).' '.$page_title ?></h5>
		<hr />
		<?php if ($messages): ?>
			<div class="message-list">
				<ul class="list-group list-group-flush mb-0">
				<?php foreach ($messages as $message): ?>
				<li class="list-group-item message-item px-0 py-2">
					<div class="media">
						<span data-toggle="tooltip" title="<?php echo $message['username'] ?>"><?php echo $this->model2('user', $message['user_id'])->avatar()->append_attr('class', 'mr-2') ?></span>
						<div class="media-body">
							<?php if ($allow_delete): ?>
							<div class="float-right">
								<?php echo $this->button_delete('user/messages/delete/'.$message['message_id'].'/'.url_title($message['title'])) ?>
							</div>
							<?php endif ?>
							<h6 class="mt-1 mb-0<?php echo (isset($message_id) && $message['message_id'] == $message_id) ? ' font-weight-bold' : '' ?>"><?php echo icon($message['unread'] ? 'fas fa-envelope text-primary' : 'far fa-envelope-open') ?> <a href="<?php echo url('user/messages/'.$message['message_id'].'/'.url_title($message['title']).(in_array($box, array('sent', 'archives')) ? '/'.$box : '')) ?>"><?php echo mb_strimwidth($message['title'], 0, 22, '...') ?></a></h6>
							<small class="text-muted"><?php echo icon('far fa-clock').' '.time_span($message['date']) ?></small>
						</div>
					</div>
				</li>
				<?php endforeach ?>
				</ul>
			</div>
		<?php else: ?>
		<div class="alert alert-info">Aucun message</div>
		<?php endif ?>
	</div>
</div>
<div class="card col-lg-7 px-0">
	<div class="message-open">
		<?php if (isset($title)): ?>
		<div class="card-body">
			<h5><?php echo $title ?></h5>
			<?php foreach ($replies as $reply): ?>
			<div class="media message">
				<?php echo $this->model2('user', $reply['user_id'])->avatar()->append_attr('class', 'mr-2') ?>
				<div class="media-body">
					<h6 class="mt-1 mb-0"><?php echo $this->user->link($reply['user_id'], $reply['username']) ?></h6>
					<p><small class="text-muted"><?php echo icon('far fa-clock').' '.time_span($reply['date']) ?></small></p>
					<?php echo bbcode($reply['message']) ?>
				</div>
			</div>
			<hr />
			<?php endforeach ?>
			<h4>Répondre</h4>
			<?php echo $form_reply->display() ?>
		</div>
		<?php endif ?>
	</div>
</div>
