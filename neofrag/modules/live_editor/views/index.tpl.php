<form target="live-editor-iframe" action="<?php echo url(); ?>" method="post">
	<input type="hidden" name="live_editor" value="<?php echo $live_editor = $NeoFrag->session('live_editor') ?: NeoFrag::LIVE_EDITOR ^ NeoFrag::WIDGETS; ?>" />
	<nav class="live-editor-navbar">
		<a class="logo" href="<?php echo url('live-editor.html'); ?>"></a>
		<a class="modules-links" data-toggle="collapse" href="#modules-links-collapse" aria-expanded="false"><?php echo icon('fa-link').' '.i18n('navigation').' '.icon('fa-angle-down'); ?></a>
		<div id="modules-links-collapse" class="collapse">
			<ul class="list-unstyled no-margin">
				<?php foreach ($data['modules'] as $name => $title): ?>
					<li><a href="<?php echo url($name.'.html'); ?>"><?php echo $title; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="pull-right">
			<p class="hidden-xs hidden-sm"><?php echo i18n('content_management'); ?></p>
			<div class="btn-group">
				<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::ZONES ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::ZONES; ?>"><?php echo icon('fa-square-o').' '.i18n('zones'); ?></button>
				<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::ROWS ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::ROWS; ?>"><?php echo icon('fa-columns fa-rotate-270').' '.i18n('rows'); ?></button>
				<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::COLS ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::COLS; ?>"><?php echo icon('fa-columns').' '.i18n('cols'); ?></button>
				<button type="button" class="live-editor-mode active" data-mode="<?php echo NeoFrag::WIDGETS; ?>"><?php echo icon('fa-th-large').' '.i18n('widgets'); ?></button>
			</div>
			<p class="hidden-xs hidden-sm"><?php echo i18n('simulate_display'); ?></p>
			<div class="btn-group">
				<button type="button" class="live-editor-screen dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<?php echo icon('fa-desktop'); ?>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><button type="button" class="live-editor-screen active" data-width="100%" data-toggle="tooltip" data-placement="bottom" title="<?php echo i18n('desktop'); ?>"><?php echo icon('fa-desktop'); ?></button></li>
					<li><button type="button" class="live-editor-screen" data-width="992px" data-toggle="tooltip" data-placement="bottom" title="<?php echo i18n('landscape_tablet'); ?>"><?php echo icon('fa-tablet fa-rotate-270'); ?></button></li>
					<li><button type="button" class="live-editor-screen" data-width="768px" data-toggle="tooltip" data-placement="bottom" title="<?php echo i18n('portrait_tablet'); ?>"><?php echo icon('fa-tablet'); ?></button></li>
					<li><button type="button" class="live-editor-screen" data-width="400px" data-toggle="tooltip" data-placement="bottom" title="<?php echo i18n('mobile'); ?>"><?php echo icon('fa-mobile'); ?></button></li>
				</ul>
			</div>
			<a href="<?php echo url(); ?>" class="live-editor-close"><?php echo icon('fa-power-off'); ?></a>
		</div>
	</nav>
</form>
<?php echo icon('fa-floppy-o live-editor-save'); ?>
<h4 id="live-editor-map"><?php echo icon('fa-spinner fa-spin').' '.i18n('loading'); ?></h4>
<div class="live-editor-styles-row">
	<?php echo $data['styles_row']; ?>
</div>
<div class="live-editor-styles-widget">
	<?php echo $data['styles_widget']; ?>
</div>
<div class="live-editor-iframe">
	<iframe name="live-editor-iframe" src=""></iframe>
</div>