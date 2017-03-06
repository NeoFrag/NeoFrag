<div id="comment-<?php echo $data['comment_id']; ?>" class="media<?php if ($data['parent_id'] !== NULL) echo ' comments-child'; ?>">
	<div class="media-left">
		<?php echo $this->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']); ?>
	</div>
	<div class="media-body">
		<?php
			$actions = [];
			
			if ($this->user() && $data['parent_id'] == NULL)
			{
				$actions[] = '<a class="comment-reply" href="#" data-comment-id="'.$data['comment_id'].'">'.icon('fa-mail-reply').' '.$this->lang('reply').'</a>';
			}
			
			if ($this->user('admin') || ($this->user() && $this->user('user_id') == $data['user_id']))
			{
				$actions[] = $this->button_delete('ajax/comments/delete/'.$data['comment_id']);
			}
			
			if ($actions)
			{
				echo '<div class="pull-right">'.implode($actions).'</div>';
			}
		?>
		<h4 class="media-heading">
			<?php echo $data['user_id'] ? $this->user->link($data['user_id'], $data['username']) : $this->lang('guest'); ?>
			<small><?php echo icon('fa-clock-o').' '.time_span($data['date']); ?></small>
		</h4>
		<?php echo $data['content'] ? strtolink(nl2br($data['content']), TRUE) : '<i>'.$this->lang('removed_message').'</i>'; ?>
	</div>
</div>
<hr<?php if ($data['parent_id'] !== NULL) echo ' class="comments-child"'; ?> />