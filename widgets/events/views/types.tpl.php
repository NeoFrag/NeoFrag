<?php if ($data['types']): ?>
<div class="list-group">
	<?php foreach ($data['types'] as $type): ?>
	<a href="<?php echo url('events/type/'.$type['type_id'].'/'.url_title($type['title'])); ?>" class="list-group-item<?php echo ($this->url->request == 'events/type/'.$type['type_id'].'/'.url_title($type['title'])) ? ' active' : ''; ?>">
		<div class="pull-right">
			<?php echo $type['nb_events']; ?>
		</div>
		<?php echo $this->label('', $type['icon'], $type['color']).'&nbsp;&nbsp;'.$type['title']; ?>
	</a>
	<?php endforeach; ?>
</div>
<?php else: ?>
<div class="panel-body">Aucun type d'événement...</div>
<?php endif; ?>