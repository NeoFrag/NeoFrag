<table class="table table-striped table-bordered table-hover">
	<?php foreach ($data['messages'] as $message): ?>
	<tr>
		<td><a href="{base_url}members/<?php echo $message['user_id']; ?>/<?php echo url_title($message['username']); ?>.html"><?php echo $message['username']; ?></a></td>
		<td><?php echo $message['content']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>