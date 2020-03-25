<div class="media<?php if ($comment->parent()) echo ' comments-child' ?>">
	<?php echo $comment->user->avatar() ?>
	<div class="media-body">
		<?php
			$actions = $this->array()
							//->append_if($this->user() && !$comment->parent(), '<a class="btn btn-link btn-sm" href="#" data-comment-id="'.$comment->id.'">'.icon('fas fa-reply').' '.$this->lang('Répondre').'</a>')//TODO
							->append_if($this->user->admin || ($this->user() && $this->user->id == $comment->user->id), $this->button_delete('ajax/comments/delete/'.$comment->id)->compact());

			if ($actions)
			{
				echo '<ul class="list-inline float-right">'.$actions->each(function($a){
					return '<li class="list-inline-item">'.$a.'</li>';
				}).'</ul>';
			}
		?>
		<h6>
			<?php echo $comment->user() ? $comment->user->link() : $this->lang('Visiteur') ?>
			<small><?php echo icon('far fa-clock').' '.$comment->date ?></small>
		</h6>
		<?php echo $comment->content ? strtolink(nl2br($comment->content), TRUE) : '<i>'.$this->lang('Message supprimé').'</i>' ?>
	</div>
</div>
