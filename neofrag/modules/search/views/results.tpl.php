<div class="list-group">
	<a href="<?php echo url('search/'.rawurlencode($data['keywords']).'.html'); ?>" class="list-group-item<?php echo preg_match('#^search(/[^/]+?)?\.html$#', $NeoFrag->config->request_url) ? ' active' : ''; ?>"><?php echo i18n('all_results'); ?> <span class="badge"><?php echo $data['count']; ?></span></a>
	<?php foreach ($data['results'] as $results): ?>
		<a href="<?php echo url('search/'.rawurlencode($data['keywords']).'/'.$results[0]->name.'.html'); ?>" class="list-group-item<?php echo preg_match('#^search/.+?/'.$results[0]->name.'(/page/\d)?\.html$#', $NeoFrag->config->request_url) ? ' active' : ''; ?>"><?php echo icon($results[0]->icon).' '.$results[0]->get_title().'<span class="badge">'.$results[3].'</span>'; ?></a>
	<?php endforeach; ?>
</div>