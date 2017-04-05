<ul class="list-group">
	<?php foreach ($data['recruits'] as $recruit): ?>
	<li class="list-group-item">
		<div class="pull-right">
			<?php if ($recruit['closed'] || ($recruit['candidacies_accepted'] >= $recruit['size']) || ($recruit['date_end'] && strtotime($recruit['date_end']) < time())): ?>
				<span class="label label-danger">Clôturée</span>
			<?php else: ?>
				<?php if ($recruit['team_id']): ?>
				<span class="label label-default" data-toggle="tooltip" title="Pour intégrer l'équipe <?php echo $recruit['team_name']; ?>"><?php echo icon('fa-gamepad'); ?></span>
				<?php endif; ?>
				<span class="label label-default" data-toggle="tooltip" title="<?php echo ($recruit['size'] - $recruit['candidacies_accepted']) > 1 ? 'Postes' : 'Poste'; ?>"><?php echo icon('fa-briefcase').' '.($recruit['size'] - $recruit['candidacies_accepted']); ?></span>
			<?php endif; ?>
		</div>
		<a href="<?php echo url('recruits/'.$recruit['recruit_id'].'/'.url_title($recruit['title'])); ?>"><?php echo ($recruit['icon'] ? icon($recruit['icon']) : icon('fa-bullhorn')).' '.str_shortener($recruit['title'], 30); ?></a>
	</li>
	<?php endforeach; ?>
</ul>