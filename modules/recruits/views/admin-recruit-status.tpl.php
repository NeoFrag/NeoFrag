<div class="row">
	<div class="col-md-4 text-center">
		<input class="knob" type="text" value="<?php echo $data['candidacies_pending']; ?>" data-thickness="0.15" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo $data['total_candidacies']; ?>" data-width="60" data-height="50" data-fgColor="#777" data-displayInput="true" data-readonly="true" autocomplete="off" />
		<p><i>En attente</i></p>
	</div>
	<div class="col-md-4 text-center">
		<input class="knob" type="text" value="<?php echo $data['candidacies_accepted']; ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo $data['size']; ?>" data-width="60" data-height="50" data-fgColor="#5cb85c" data-displayInput="true" data-readonly="true" autocomplete="off" />
		<p><i>Validées</i></p>
	</div>
	<div class="col-md-4 text-center">
		<input class="knob" type="text" value="<?php echo $data['candidacies_declined']; ?>" data-thickness="0.15" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo $data['total_candidacies']; ?>" data-width="60" data-height="50" data-fgColor="#d9534f" data-displayInput="true" data-readonly="true" autocomplete="off" />
		<p><i>Refusées</i></p>
	</div>
</div>
<div class="text-center">
	<?php if ($data['available']): ?>
	<h4><?php echo '<b> '.$data['available'].($data['available'] > 1 ? ' places</b> libres' : ' place</b> libre'); ?></h4>
	<ul class="list-inline">
		<?php
		for ($i = 1; $i <= $data['candidacies_accepted']; $i++) {
			echo '<li style="color:#7bbb17;">'.icon('fa-circle').'</li>';
		}
		for ($i = 1; $i <= $data['available']; $i++) {
			echo '<li class="text-muted">'.icon('fa-circle-o').'</li>';
		}
		?>
	</ul>
	<?php else: ?>
	<div class="alert alert-success no-margin"><?php echo icon('fa-check'); ?> Offre complète !</div>
	<?php endif; ?>
</div>