<div class="list-group">
	<a href="<?php echo url('search?q='.rawurlencode($data['keywords'])) ?>" class="list-group-item<?php echo $this->url->request == 'search' ? ' active' : '' ?>"><?php echo $this->lang('Tous les résultats') ?> <span class="badge"><?php echo $data['count'] ?></span></a>
	<?php foreach ($data['results'] as $results): ?>
		<a href="<?php echo url('search/'.$results[0]->info()->name.'?q='.rawurlencode($data['keywords'])) ?>" class="list-group-item<?php echo preg_match('#^search/'.$results[0]->info()->name.'/?#', $this->url->request) ? ' active' : '' ?>"><?php echo icon($results[0]->info()->icon).' '.$results[0]->info()->title.'<span class="badge">'.$results[3].'</span>' ?></a>
	<?php endforeach ?>
</div>
