<div id="comment-<?php echo $comment_id ?>" class="media<?php if ($parent_id !== NULL) echo ' comments-child' ?>">
	<div class="media-left">
		<?php echo NeoFrag()->model2('user', $user_id)->avatar() ?>
	</div>
	<div class="media-body">
		<?php
			$actions = [];

			if ($this->user() && $parent_id == NULL)
			{
				$actions[] = '<a class="comment-reply" href="#" data-comment-id="'.$comment_id.'">'.icon('fa-mail-reply').' '.$this->lang('Répondre').'</a>';
			}

			if ($this->user->admin || ($this->user() && $this->user->id == $user_id))
			{
				$actions[] = $this->button_delete('ajax/comments/delete/'.$comment_id);
			}

			if ($actions)
			{
				echo '<div class="pull-right">'.implode($actions).'</div>';
			}
		?>
		<h4 class="media-heading">
			<?php echo $user_id ? $this->user->link($user_id, $username) : $this->lang('Visiteur') ?>
			<small><?php echo icon('fa-clock-o').' '.time_span($date) ?></small>
		</h4>
		<?php echo $content ? strtolink(nl2br($content), TRUE) : '<i>'.$this->lang('Message supprimé').'</i>' ?>
	</div>
</div>
<hr<?php if ($parent_id !== NULL) echo ' class="comments-child"' ?> />
