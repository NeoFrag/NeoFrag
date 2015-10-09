<table class="table table-hover table-stripped">
	<tr>
		<td width="20%"><?php echo i18n('operatig_system'); ?></td>
		<td><?php echo php_uname(); ?></td>
	</tr>
	<tr>
		<td><?php echo i18n('web_server'); ?></td>
		<td><?php echo preg_match('#(.+?)/(.+?) #', $_SERVER['SERVER_SOFTWARE'], $match) ? $match[1].' '.$match[2] : $_SERVER['SERVER_SOFTWARE']; ?></td>
	</tr>
	<tr>
		<td><?php echo i18n('databases_server'); ?></td>
		<td><?php echo $NeoFrag->db->get_info(); ?></td>
	</tr>
	<tr>
		<td><?php echo i18n('loaded_extensions'); ?></td>
		<td>
			<ul class="extensions">
			<?php foreach ($data['extensions'] as $extension): ?>
				<li><a href="#module_<?php echo $extension; ?>"><?php echo icon('fa-puzzle-piece').' '.$extension; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</td>
	</tr>
</table>
