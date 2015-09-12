<table class="table table-striped table-bordered table-hover">
	<?php foreach ($data['messages'] as $message): ?>
	<tr>
		<td><a href="<?php echo url('members/'.$message['user_id'].'/'.url_title($message['username']).'.html'); ?>"><?php echo $message['username']; ?></a></td>
		<td><?php echo $message['content']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>