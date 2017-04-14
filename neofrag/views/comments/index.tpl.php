<div id="comment-<?php echo $id ?>" class="media<?php if ($parent_id !== NULL) echo ' comments-child' ?>">
	<div class="media-left">
		<?php echo $this->model2('user', $user_id)->avatar() ?>
	</div>
	<div class="media-body">
		<?php
			$actions = [];

			if ($this->user->id && $parent_id == NULL)
			{
				$actions[] = '<a class="comment-reply" href="#" data-comment-id="'.$id.'">'.icon('fa-mail-reply').' '.$this->lang('reply').'</a>';
			}

			if ($this->user->admin || ($this->user->id && $this->user->id == $user_id))
			{
				$actions[] = $this->button_delete('ajax/comments/delete/'.$id);
			}

			if ($actions)
			{
				echo '<div class="pull-right">'.implode($actions).'</div>';
			}
		?>
		<h4 class="media-heading">
			<?php echo $user_id ? $this->user->link($user_id, $username) : $this->lang('guest') ?>
			<small><?php echo icon('fa-clock-o').' '.time_span($date) ?></small>
		</h4>
		<?php echo $content ? strtolink(nl2br($content), TRUE) : '<i>'.$this->lang('removed_message').'</i>' ?>
	</div>
</div>
<hr<?php if ($parent_id !== NULL) echo ' class="comments-child"' ?> />
