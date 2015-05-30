<div id="comment-{comment_id}" class="media<?php if (!is_null($data['parent_id'])) echo ' comments-child'; ?>">
	<div class="media-left">
		<a href="{base_url}members/{user_id}/{url_title(username)}.html">
			<img class="media-object" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" style="max-width: 64px; max-height: 64px;" alt="" />
		</a>
	</div>
	<div class="media-body">
		<?php
			$actions = array();
			
			if ($NeoFrag->user() && is_null($data['parent_id']))
			{
				$actions[] = '<a class="comment-reply" href="#" data-comment-id="'.$data['comment_id'].'">'.$NeoFrag->assets->icon('fa-mail-reply').' Répondre</a>';
			}
			
			if ($NeoFrag->user('admin') || $NeoFrag->user('user_id') == $data['user_id'])
			{
				$actions[] = button_delete($this->config->base_url.'ajax/comments/delete/'.$data['comment_id'].'.html');
			}
			
			if ($actions)
			{
				echo '<div class="pull-right">'.implode($actions).'</div>';
			}
		?>
		<h4 class="media-heading">
			<?php echo $NeoFrag->user->link($data['user_id'], $data['username']); ?>
			<small>{fa-icon clock-o} <?php echo time_span($data['date']); ?></small>
		</h4>
		<?php echo $data['content'] ? strtolink($data['content']) : '<i>Message supprimé</i>'; ?>
	</div>
</div>
<hr<?php if (!is_null($data['parent_id'])) echo ' class="comments-child"'; ?> />