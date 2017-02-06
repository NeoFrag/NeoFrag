<div class="list-group">
	<a href="<?php echo url('search.html?q='.rawurlencode($data['keywords'])); ?>" class="list-group-item<?php echo $NeoFrag->url->request == 'search.html' ? ' active' : ''; ?>"><?php echo i18n('all_results'); ?> <span class="badge"><?php echo $data['count']; ?></span></a>
	<?php foreach ($data['results'] as $results): ?>
		<a href="<?php echo url('search/'.$results[0]->name.'.html?q='.rawurlencode($data['keywords'])); ?>" class="list-group-item<?php echo preg_match('#^search/'.$results[0]->name.'/?#', $NeoFrag->url->request) ? ' active' : ''; ?>"><?php echo icon($results[0]->icon).' '.$results[0]->get_title().'<span class="badge">'.$results[3].'</span>'; ?></a>
	<?php endforeach; ?>
</div>