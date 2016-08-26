<?php
$count = count($data['messages']);

if (!empty($data['user_id']) && !empty($data['position']))
{
	$user_id = $data['user_id'];
	$media   = $data['position'];
	
	if ($count)
	{
		echo '<hr style="margin: 15px 0;" />';
	}
}

foreach ($data['messages'] as $i => $message)
{
	if (!isset($user_id) || $user_id != $message['user_id'])
	{
		$media = isset($media) && $media == 'left' ? 'right' : 'left';
	}
	
	if (!isset($media))
	{
		$media = 'left';
	}
?>
<div class="media" data-message-id="<?php echo $message['message_id']; ?>" data-position="<?php echo $media; ?>">
<?php
	ob_start();
?>
	<div class="media-<?php echo $media; ?>">
		<?php if ($message['user_id']): ?>
			<?php echo $NeoFrag->user->avatar($message['avatar'], $message['sex'], $message['user_id'], $message['username']); ?>
		<?php else: ?>
			<?php echo $NeoFrag->user->avatar(NULL); ?>
		<?php endif; ?>
	</div>
<?php
	$avatar = ob_get_clean();
	ob_start();
?>
	<div class="media-body<?php if ($media == 'right') echo ' text-right'; ?>">
		<?php
			if (($NeoFrag->user() && $NeoFrag->user('user_id') == $message['user_id']) || $NeoFrag->access('talks', 'delete', $message['talk_id']))
			{
				echo '<div class="pull-'.($media == 'right' ? 'left' : 'right').'">'.button_delete('ajax/talks/delete/'.$message['message_id'].'.html').'</div>';
			}
		?>
		<h4 class="media-heading">
		<?php
			$title = [$message['user_id'] ? $NeoFrag->user->link($message['user_id'], $message['username']) : '<i>'.i18n('guest').'</i>', '<small>'.icon('fa-clock-o').' '.time_span($message['date']).'</small>'];
			
			if ($media == 'right')
			{
				$title = array_reverse($title);
			}
			
			echo implode(' ', $title);
		?>
		</h4>
		<?php echo $message['message'] ? strtolink($message['message']) : '<i>'.i18n('removed_message').'</i>'; ?>
	</div>
<?php
	$output = [$avatar, ob_get_clean()];
	
	if ($media == 'right')
	{
		$output = array_reverse($output);
	}
	
	echo implode($output);
?>
</div>
<?php
	if ($i < $count - 1)
	{
		echo '<hr style="margin: 15px 0;" />';
	}
	
	$user_id = $message['user_id']; 
}
?>
<?php if (!$count && empty($data['user_id']) && empty($data['position'])): ?>
	<div class="text-center"><?php echo i18n('no_messages'); ?></div>
<?php endif; ?>