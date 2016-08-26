<div id="comment-<?php echo $data['comment_id']; ?>" class="media<?php if ($data['parent_id'] !== NULL) echo ' comments-child'; ?>">
	<div class="media-left">
		<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']); ?>
	</div>
	<div class="media-body">
		<?php
			$actions = [];
			
			if ($NeoFrag->user() && $data['parent_id'] == NULL)
			{
				$actions[] = '<a class="comment-reply" href="#" data-comment-id="'.$data['comment_id'].'">'.icon('fa-mail-reply').' '.$NeoFrag->lang('reply').'</a>';
			}
			
			if ($NeoFrag->user('admin') || ($NeoFrag->user() && $NeoFrag->user('user_id') == $data['user_id']))
			{
				$actions[] = button_delete('ajax/comments/delete/'.$data['comment_id'].'.html');
			}
			
			if ($actions)
			{
				echo '<div class="pull-right">'.implode($actions).'</div>';
			}
		?>
		<h4 class="media-heading">
			<?php echo $data['user_id'] ? $NeoFrag->user->link($data['user_id'], $data['username']) : i18n('guest'); ?>
			<small><?php echo icon('fa-clock-o').' '.time_span($data['date']); ?></small>
		</h4>
		<?php echo $data['content'] ? strtolink(nl2br($data['content']), TRUE) : '<i>'.$NeoFrag->lang('removed_message').'</i>'; ?>
	</div>
</div>
<hr<?php if ($data['parent_id'] !== NULL) echo ' class="comments-child"'; ?> />