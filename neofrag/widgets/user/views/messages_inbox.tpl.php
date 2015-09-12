<?php if (empty($data['messages'])): ?>
<div class="panel-body text-center">
	<?php echo icon('fa-envelope-o'); ?> Vous n'avez pas de nouveau message.
</div>
<?php else: ?>
<div class="panel-body text-center">
	<?php echo icon('fa-envelope-o'); ?> Vous avez <b><?php echo count($data['messages']); ?> messages</b> non lus !
</div>
<table class="table">
	<tbody>
		<?php foreach ($data['messages'] as $messages): ?>
		<tr>
			<td class="col-md-2 text-center">
				<a href="<?php echo url('members/'.$messages['user_id'].'/'.url_title($messages['username']).'.html'); ?>">
					<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar($messages['avatar'], $messages['sex']); ?>" alt="" />
				</a>
			</td>
			<td class="col-md-8 col-xs-7">
				<p><?php echo icon('fa-user').' '.$NeoFrag->user->link($messages['user_id'], $messages['username']).' '.icon('fa-clock-o').' '.echo time_span($messages['date']); ?></p>
				<p><?php echo bbcode($messages['content']); ?></p>
			</td>
			<td class="col-md-2 col-xs-3 text-right">
				<a class="btn btn-primary btn-xs" href="#"><?php echo icon('fa-envelope'); ?></a>
				<a class="btn btn-danger btn-xs" href="#"><?php echo icon('fa-close'); ?></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>