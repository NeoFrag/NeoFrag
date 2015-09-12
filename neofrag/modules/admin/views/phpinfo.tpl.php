<table class="table table-hover table-stripped">
	<tr>
		<td width="20%">Système d'exploitation</td>
		<td><?php echo php_uname(); ?></td>
	</tr>
	<tr>
		<td>Serveur web</td>
		<td><?php echo preg_match('#(.+?)/(.+?) #', $_SERVER['SERVER_SOFTWARE'], $match) ? $match[1].' '.$match[2] : $_SERVER['SERVER_SOFTWARE']; ?></td>
	</tr>
	<tr>
		<td>Serveur de base de données</td>
		<td><?php echo $NeoFrag->db->get_info(); ?></td>
	</tr>
	<tr>
		<td>Extensions chargées</td>
		<td>
			<ul class="extentions">
			<?php foreach ($data['extentions'] as $extention): ?>
				<li><a href="#module_<?php echo $extention; ?>"><?php echo icon('fa-puzzle-piece').' '.$extention; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</td>
	</tr>
</table>
