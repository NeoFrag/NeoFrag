<table class="table table-hover table-stripped">
	<tr>
		<td width="20%"><?php echo $this->lang('Système d\'exploitation') ?></td>
		<td><?php echo php_uname() ?></td>
	</tr>
	<tr>
		<td width="20%">Serveur PHP</td>
		<td><?php echo $php_server ?></td>
	</tr>
	<tr>
		<td><?php echo $this->lang('Serveur web') ?></td>
		<td><?php echo $web_server ?></td>
	</tr>
	<tr>
		<td><?php echo $this->lang('Serveur de bases de données') ?></td>
		<td><?php echo $databases_server ?></td>
	</tr>
	<tr>
		<td><?php echo $this->lang('Extensions chargées') ?></td>
		<td>
			<ul class="extensions">
			<?php foreach ($extensions as $extension): ?>
				<li><a href="#module_<?php echo $extension ?>"><?php echo icon('fas fa-puzzle-piece').' '.$extension ?></a></li>
			<?php endforeach ?>
			</ul>
		</td>
	</tr>
</table>
