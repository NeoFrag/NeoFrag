<?php
$count = count($messages);

if (!empty($user_id) && !empty($position))
{
	$user_id = $user_id;
	$media   = $position;

	if ($count)
	{
		echo '<hr style="margin: 15px 0;" />';
	}
}

foreach ($messages as $i => $message)
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
<div class="media" data-message-id="<?php echo $message['message_id'] ?>" data-position="<?php echo $media ?>">
<?php
	ob_start();
?>
	<div class="media-<?php echo $media ?>">
		<?php echo $this->module('user')->model2('user', $message['user_id'])->avatar() ?>
	</div>
<?php
	$avatar = ob_get_clean();
	ob_start();
?>
	<div class="media-body<?php if ($media == 'right') echo ' text-right' ?>">
		<?php
			if (($this->user() && $this->user->id == $message['user_id']) || $this->access('talks', 'delete', $message['talk_id']))
			{
				echo '<div class="float-'.($media == 'right' ? 'left' : 'right').'">'.$this->button_delete('ajax/talks/delete/'.$message['message_id']).'</div>';
			}
		?>
		<h6>
		<?php
			$title = [$message['user_id'] ? $this->user->link($message['user_id'], $message['username']) : '<i>'.$this->lang('Visiteur').'</i>', '<small>'.icon('far fa-clock').' '.time_span($message['date']).'</small>'];

			if ($media == 'right')
			{
				$title = array_reverse($title);
			}

			echo implode(' ', $title);
		?>
		</h6>
		<?php echo $message['message'] ? strtolink($message['message']) : '<i>'.$this->lang('Message supprimé').'</i>' ?>
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
<?php if (!$count && empty($user_id) && empty($position)): ?>
	<div class="text-center"><?php echo $this->lang('Aucun message dans la discussion') ?></div>
<?php endif ?>
