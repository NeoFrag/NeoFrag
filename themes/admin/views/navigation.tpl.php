<ul>
	<?php
	foreach ([
		[icon('pe-7s-home'),             url()],
		[$this->lang('Fonctionnalités'), 'https://neofr.ag/#features'],
		[$this->lang('Thèmes & Addons'), 'https://addons.neofr.ag'],
		[$this->lang('Documentation'),   'https://docs.neofr.ag'],
		[$this->lang('Forum'),           'https://neofr.ag/forum'],
		[$this->lang('Blog'),            'https://neofr.ag/blog']
	] as list($title, $url)): ?>
		<li>
			<a href="<?php echo $url ?>"><?php echo $title ?></a>
		</li>
	<?php endforeach ?>
</ul>
<form class="socials" method="get" action="https://neofr.ag/search">
	<?php foreach ([
		['facebook', 'https://www.facebook.com/NeoFragCMS'],
		['twitter',  'https://twitter.com/NeoFragCMS'],
		['github',   'https://github.com/NeoFragCMS/neofrag-cms']
	] as list($name, $url)): ?>
		<a class="btn <?php echo $name ?>" href="<?php echo $url ?>"><?php echo icon('fa-'.$name) ?></a>
	<?php endforeach ?>
	<button type="submit" class="btn"><?php echo icon('fa-search') ?></button>
	<input type="text" name="q" placeholder="<?php echo $this->lang('Recherche...') ?>">
</form>
