<div class="row-fluid">
	<div class="pull-left" style="width:20%;">
		<div class="navbar-inner" style="padding:5px; margin-right:10px;">
			<ul class="nav nav-list">
				<li<?php echo (preg_match('#search(/[^/]+?)?\.html#', $NeoFrag->config->request_url)) ? ' class="active"' : ''; ?>><a href="<?php echo url('search/'.$data['search'].'.html'); ?>">Tous</a></li>
				<li class="divider"></li>
				<?php foreach ($data['modules'] as $module): ?>
					<li<?php echo (preg_match('#search/.+?/'.$module['name'].'.html#', $NeoFrag->config->request_url)) ? ' class="active"' : ''; ?>><a href="<?php echo url('search/'.$data['search'].'/'.$module['name'].'.html'); ?>"><?php echo $module['title']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="pull-right" style="width:80%;">
		<?php foreach ($data['modules'] as $module):
				if ($module['display']): ?>
					<div class="row-fluid">
						<?php if (!$data['details']): ?>
							<h3><a href="<?php echo url('search/'.$data['search'].'/'.$module['name'].'.html'); ?>"><?php echo $module['title']; ?></a></h3>
						<?php endif;
						echo $module['display']; ?>
					</div>
		<?php 	endif;
			endforeach; ?>
	</div>
</div>