<div class="row">
	<div class="col-md-6">
		<h4 class="no-margin"><b>Mes informations</b></h4>
		<hr />
		<?php if ($first_name || $last_name): ?>
		<p><?php echo ($sex == 'male' ? icon('fa-male') : icon('fa-female')).' '.$first_name.' '.$last_name ?></p>
		<?php endif ?>

		<?php if (!empty($date_of_birth)): ?>
		<p><?php echo icon('fa-birthday-cake').' '.timetostr($this->lang('%d/%m/%Y'), $date_of_birth).' '.$this->lang('(%d an)|(%d ans)', $age = date_diff(date_create($date_of_birth), date_create('today'))->y, $age) ?></p>
		<?php endif ?>

		<?php if ($location): ?>
		<p><?php echo icon('fa-map-marker').' '.$location ?></p>
		<?php endif ?>

		<?php if ($website): ?>
		<p><?php echo icon('fa-globe') ?> <a href="<?php echo $website ?>" target="_blank"><?php echo $website ?></a></p>
		<?php endif ?>

		<?php if ($quote): ?>
		<p><?php echo icon('fa-quote-left') ?> <i class="text-muted"><?php echo $quote ?></i></p>
		<?php endif ?>
	</div>
	<div class="col-md-6">
		<h4 class="no-margin"><b>Messagerie</b></h4>
		<hr />
		<?php if ($messages = $this->user->get_messages()): ?>
		<a href="<?php echo url('user/messages') ?>" class="btn btn-info btn-block btn-lg">Vous avez <?php echo $messages > 0 ? $messages.' messages non lus !' : '1 message non lu !' ?></a>
		<?php else: ?>
		Aucun nouveau message...
		<?php endif ?>
	</div>
</div>
