<div class="widget">
	<div class="widget-header"><h3>NeoFrag <small>Composants du noyau version <?php echo NEOFRAG_VERSION; ?></small></h3></div>
	<table class="table table-hover">
		<tr class="error">
			<td class="title alert-error" colspan="3">Modules</td>
		</tr>
		<?php foreach ($data['nf_modules'] as $module): ?>
		<tr>
			<td><img src="<?php echo image('icons-24/newspaper.png'); ?>" alt="" /></td>
			<td><h1><?php echo $module['title']; ?><small><?php echo $module['description']; ?></small></h1></td>
			<td><a href="" class="btn">Désactiver</a></td>
		</tr>
		<?php endforeach; ?>
		<tr class="success">
			<td class="title alert-success" colspan="3">Widgets</td>
		</tr>
		<?php foreach ($data['nf_widgets'] as $widget): ?>
		<tr>
			<td><img src="<?php echo image('icons-24/newspaper.png'); ?>" alt="" /></td>
			<td><h1><?php echo $widget['title']; ?><small></small></h1></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
		<tr class="info">
			<td class="title alert-info" colspan="3">Thèmes</td>
		</tr>
		<?php foreach ($data['nf_themes'] as $theme): ?>
		<tr>
			<td><img src="<?php echo image('icons-24/newspaper.png'); ?>" alt="" /></td>
			<td><h1><?php echo $theme['title']; ?><small></small></h1></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<div class="widget">
	<div class="widget-header"><h3>Modules</h3></div>
	<table class="table table-hover">
		<?php foreach ($data['modules'] as $module): ?>
		<tr>
			<td><img src="<?php echo image('icons-24/newspaper.png'); ?>" alt="" /></td>
			<td><h1><?php echo $module['title']; ?><small></small></h1></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<div class="widget">
	<div class="widget-header"><h3>Widgets</h3></div>
	<table class="table table-hover">
		<?php foreach ($data['widgets'] as $widget): ?>
		<tr>
			<td><img src="<?php echo image('icons-24/newspaper.png'); ?>" alt="" /></td>
			<td><h1><?php echo $widget['title']; ?><small></small></h1></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<div class="widget">
	<div class="widget-header"><h3>Thèmes</h3></div>
	<table class="table table-hover">
		<?php foreach ($data['themes'] as $theme): ?>
		<tr>
			<td><img src="<?php echo image('icons-24/newspaper.png'); ?>" alt="" /></td>
			<td><h1><?php echo $theme['title']; ?><small></small></h1></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>