<div class="debug-bar hidden-xs<?php if ($active) echo ' active' ?>">
	<div class="debug-bar-resize"></div>
	<nav>
		<div class="logo hidden-sm"></div>
		<?php foreach ($tabs as $name => $tab): ?>
			<div class="debug-bar-tab<?php if ($active == $name) echo ' active' ?>" data-debug-bar="<?php echo $name ?>">
				<?php echo icon($tab[1]) ?>
				<span class="hidden-sm hidden-md"> <?php echo $tab[0] ?></span>
				<?php if (!empty($tab[3])) echo ' '.$tab[3] ?>
				<?php if($name == "console") echo '<span class="dropdown">
						<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
						'.icon('fas fa-filter').' Filter
						</button>
						<div class="dropdown-menu keep-open">
							<a class="dropdown-item console-filter" style="line-height: initial;" data-filter="info">'.icon('far fa-square').' Info</a>
							<a class="dropdown-item console-filter" style="line-height: initial;" data-filter="warning">'.icon('far fa-check-square').' Warning</a>
							<a class="dropdown-item console-filter" style="line-height: initial;" data-filter="error">'.icon('far fa-check-square').' Error</a>
							<a class="dropdown-item console-filter" style="line-height: initial;" data-filter="notice">'.icon('far fa-check-square').' Notice</a>
							<a class="dropdown-item console-filter" style="line-height: initial;" data-filter="deprecated">'.icon('far fa-check-square').' Deprecated</a>
							<a class="dropdown-item console-filter" style="line-height: initial;" data-filter="strict">'.icon('far fa-check-square').' Scrict</a>
						</div>
					</span>
			  	' ?>
			</div>
		<?php endforeach ?>
		<div class="float-right">
			<p class="hidden-sm"><?php echo '<span class="badge">'.(post() ? 'POST' : 'GET').'</span> '.implode(' ', array_map(function($a){ return '<span class="badge">'.utf8_htmlentities($a).'</span>'; }, $this->url->segments)) ?></p>
			<p><?php echo icon('far fa-clock').' '.round((microtime(TRUE) - NEOFRAG_TIME) * 1000, 2).' ms' ?></p>
			<p><?php echo icon('fas fa-cogs').' '.ceil((memory_get_peak_usage() - NEOFRAG_MEMORY) / 1024).' kB' ?></p>
			<div class="debug-bar-close<?php if ($active) echo ' active' ?>"><?php echo icon('fas fa-times') ?></div>
		</div>
	</nav>
	<div class="debug-bar-content"<?php if ($height = $this->session('debug', 'height')) echo ' style="height: '.max(200, $height).'px"' ?>>
		<?php foreach ($tabs as $name => $tab): ?>
		<div class="debug-bar-pane<?php if ($active == $name) echo ' active' ?>" data-tab="<?php echo $name ?>">
			<?php echo $tab[2] ?>
		</div>
		<?php endforeach ?>
	</div>
</div>
