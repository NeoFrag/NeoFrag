<div class="media<?php if ($comment->parent()) echo ' comments-child' ?>">
	<?php echo $comment->user->avatar() ?>
	<div class="media-body">
		<?php
			$actions = [];

			if ($this->user() && !$comment->parent())
			{
				$actions[] = '<li><a class="btn btn-link btn-sm" href="#" data-comment-id="'.$comment->id.'">'.icon('fa-mail-reply').' '.$this->lang('Répondre').'</a></li>';
			}

			if ($this->user->admin || ($this->user() && $this->user->id == $comment->user->id))
			{
				$actions[] = '<li>'.$this->button_delete('ajax/comments/delete/'.$comment->id)->compact().'</li>';
			}

			if ($actions)
			{
				echo '<ul class="list-right">'.implode($actions).'</ul>';
			}
		?>
		<h6>
			<?php echo $comment->user() ? $comment->user->link() : $this->lang('Visiteur') ?>
			<small><?php echo icon('fa-clock-o').' '.$comment->date ?></small>
		</h6>
		<?php echo $comment->content ? strtolink(nl2br($comment->content), TRUE) : '<i>'.$this->lang('Message supprimé').'</i>' ?>
	</div>
</div>
