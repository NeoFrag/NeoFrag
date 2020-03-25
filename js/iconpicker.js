$(function(){
	$('body').on('nf.load', function(){
		$('.iconpicker:not(.iconpickered)').each(function(){
			$(this)	.addClass('iconpickered')
					.iconpicker({
						arrowClass: 'btn-danger',
						arrowPrevIconClass: 'fas fa-caret-left',
						arrowNextIconClass: 'fas fa-caret-right',
						cols: 10,
						rows: 5,
						iconset: 'fontawesome',
						labelHeader: '<?php echo $this->lang('{0} sur {1} pages') ?>',
						labelFooter: '<div class="float-right"><?php echo $this->lang('{2} icônes') ?></div>',
						searchText: '<?php echo $this->lang('Rechercher...') ?>',
						selectedClass: 'btn-primary',
						unselectedClass: ''
					});
		});
	});
});
