<form target="live-editor-iframe" action="<?php echo url() ?>" method="post">
	<input type="hidden" name="live_editor" value="<?php echo $live_editor = $this->session('live_editor') ?: $this->output->live_editor() ^ \NF\NeoFrag\Core\Output::WIDGETS ?>" />
	<nav class="live-editor-navbar">
		<a class="logo" href="<?php echo url('admin/live-editor') ?>"></a>
		<a class="modules-links" data-toggle="collapse" href="#modules-links-collapse" aria-expanded="false"><?php echo icon('fas fa-link').' '.$this->lang('Navigation').' '.icon('fas fa-angle-down') ?></a>
		<div id="modules-links-collapse" class="collapse">
			<ul class="list-unstyled m-0">
				<?php foreach ($modules as $name => $title): ?>
					<li><a href="<?php echo url($name) ?>"><?php echo $title ?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
		<div class="float-right">
			<p class="hidden-xs hidden-sm"><?php echo $this->lang('Gestion du contenu') ?></p>
			<div class="btn-group">
				<button type="button" class="live-editor-mode<?php echo $live_editor & \NF\NeoFrag\Core\Output::ZONES ? ' active' : '' ?>" data-mode="<?php echo \NF\NeoFrag\Core\Output::ZONES ?>"><?php echo icon('far fa-square').' '.$this->lang('Zones') ?></button>
				<button type="button" class="live-editor-mode<?php echo $live_editor & \NF\NeoFrag\Core\Output::ROWS ? ' active' : '' ?>" data-mode="<?php echo \NF\NeoFrag\Core\Output::ROWS ?>"><?php echo icon('fas fa-columns fa-rotate-270').' '.$this->lang('Rows') ?></button>
				<button type="button" class="live-editor-mode<?php echo $live_editor & \NF\NeoFrag\Core\Output::COLS ? ' active' : '' ?>" data-mode="<?php echo \NF\NeoFrag\Core\Output::COLS ?>"><?php echo icon('fas fa-columns').' '.$this->lang('Cols') ?></button>
				<button type="button" class="live-editor-mode active" data-mode="<?php echo \NF\NeoFrag\Core\Output::WIDGETS ?>"><?php echo icon('fas fa-th-large').' '.$this->lang('Widgets') ?></button>
			</div>
			<p class="hidden-xs hidden-sm"><?php echo $this->lang('Simuler l\'affichage') ?></p>
			<div class="btn-group">
				<button type="button" class="live-editor-screen dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<?php echo icon('fas fa-desktop') ?>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><button type="button" class="live-editor-screen active" data-width="100%" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Ordinateur') ?>"><?php echo icon('fas fa-desktop') ?></button></li>
					<li><button type="button" class="live-editor-screen" data-width="992px" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Tablette paysage') ?>"><?php echo icon('fas fa-tablet-alt fa-rotate-270') ?></button></li>
					<li><button type="button" class="live-editor-screen" data-width="768px" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Tablette portrait') ?>"><?php echo icon('fas fa-tablet-alt') ?></button></li>
					<li><button type="button" class="live-editor-screen" data-width="400px" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Smartphone') ?>"><?php echo icon('fas fa-mobile-alt') ?></button></li>
				</ul>
			</div>
			<a href="<?php echo url() ?>" class="live-editor-close"><?php echo icon('fas fa-power-off') ?></a>
		</div>
	</nav>
</form>
<?php echo icon('far fa-save live-editor-save') ?>
<h4 id="live-editor-map"><?php echo icon('fas fa-spinner fa-spin').' '.$this->lang('Chargement en cours...') ?></h4>
<div class="live-editor-styles-row">
	<?php echo $styles_row ?>
</div>
<div class="live-editor-styles-widget">
	<?php echo $styles_widget ?>
</div>
<div class="live-editor-iframe">
	<iframe name="live-editor-iframe" src=""></iframe>
</div>
