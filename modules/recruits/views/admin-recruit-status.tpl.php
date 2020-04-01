<div class="row">
	<div class="col-4 text-center">
		<input class="knob" type="text" value="<?php echo $candidacies_pending ?>" data-thickness="0.15" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo $total_candidacies ?>" data-width="60" data-height="50" data-fgColor="#777" data-displayInput="true" data-readonly="true" autocomplete="off" />
		<p><i>En attente</i></p>
	</div>
	<div class="col-4 text-center">
		<input class="knob" type="text" value="<?php echo $candidacies_accepted ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo $size ?>" data-width="60" data-height="50" data-fgColor="#5cb85c" data-displayInput="true" data-readonly="true" autocomplete="off" />
		<p><i>Validées</i></p>
	</div>
	<div class="col-4 text-center">
		<input class="knob" type="text" value="<?php echo $candidacies_declined ?>" data-thickness="0.15" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo $total_candidacies ?>" data-width="60" data-height="50" data-fgColor="#d9534f" data-displayInput="true" data-readonly="true" autocomplete="off" />
		<p><i>Refusées</i></p>
	</div>
</div>
<div class="text-center">
	<?php if ($available): ?>
	<h4><?php echo '<b> '.$available.($available > 1 ? ' places</b> libres' : ' place</b> libre') ?></h4>
	<ul class="list-inline">
		<?php
		for ($i = 1; $i <= $candidacies_accepted; $i++) {
			echo '<li class="list-inline-item" style="color:#7bbb17;">'.icon('fas fa-circle').'</li>';
		}
		for ($i = 1; $i <= $available; $i++) {
			echo '<li class="list-inline-item text-muted">'.icon('far fa-circle').'</li>';
		}
		?>
	</ul>
	<?php else: ?>
	<div class="alert alert-success m-0"><?php echo icon('fas fa-check') ?> Offre complète !</div>
	<?php endif ?>
</div>
