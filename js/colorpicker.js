$(function(){
	$('.input-group.color').colorpicker({
		format: 'hex',
		component: '.input-group-prepend,input',
		colorSelectors: <?php echo json_encode(get_colors()) ?>
	});
});
