<div class="debugbar hidden-xs<?php if ($data['active']) echo ' active'; ?>">
	<div class="debugbar-resize"></div>
	<nav>
		<div class="logo hidden-sm"></div>
		<?php foreach ($data['tabs'] as $name => $tab): ?>
			<div class="debugbar-tab<?php if ($data['active'] == $name) echo ' active'; ?>" data-debugbar="<?php echo $name; ?>"><?php echo icon($tab[2]); ?><span class="hidden-sm hidden-md"> <?php echo $tab[1]; ?></span><?php if (!empty($tab[3])) echo ' '.$tab[3]; ?></div>
		<?php endforeach; ?>
		<div class="pull-right">
			<p class="hidden-sm"><?php echo '<span class="badge">'.(post() ? 'POST' : 'GET').'</span> '.implode(' ', array_map(function($a){ return '<span class="badge">'.utf8_htmlentities($a).'</span>'; }, NeoFrag()->router->segments)); ?></p>
			<p><?php echo icon('fa-clock-o').' '.round((microtime(TRUE) - NEOFRAG_TIME) * 1000, 2).' ms'; ?></p>
			<p><?php echo icon('fa-cogs').' '.ceil((memory_get_peak_usage() - NEOFRAG_MEMORY) / 1024).' kB'; ?></p>
			<div class="debugbar-close<?php if ($data['active']) echo ' active'; ?>"><?php echo icon('fa-close'); ?></div>
		</div>
	</nav>
	<div class="debugbar-content"<?php if ($height = $this->session('debugbar', 'height')) echo ' style="height: '.max(200, $height).'px"'; ?>>
		<?php foreach ($data['tabs'] as $name => $tab): ?>
		<div class="debugbar-pane<?php if ($data['active'] == $name) echo ' active'; ?>" data-tab="<?php echo $name; ?>">
			<?php echo $tab[0]; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
