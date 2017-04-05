<div class="row">
	<div class="col-md-6">
		<h4 class="no-margin"><b>Mes informations</b></h4>
		<hr />
		<?php if ($data['first_name'] || $data['last_name']): ?>
		<p><?php echo ($data['sex'] == 'male' ? icon('fa-male') : icon('fa-female')).' '.$data['first_name'].' '.$data['last_name']; ?></p>
		<?php endif; ?>

		<?php if (!empty($data['date_of_birth'])): ?>
		<p><?php echo icon('fa-birthday-cake').' '.timetostr($this->lang('date_short'), $data['date_of_birth']).' '.$this->lang('age', $age = date_diff(date_create($data['date_of_birth']), date_create('today'))->y, $age); ?></p>
		<?php endif; ?>

		<?php if ($data['location']): ?>
		<p><?php echo icon('fa-map-marker').' '.$data['location']; ?></p>
		<?php endif; ?>

		<?php if ($data['website']): ?>
		<p><?php echo icon('fa-globe'); ?> <a href="<?php echo $data['website']; ?>" target="_blank"><?php echo $data['website']; ?></a></p>
		<?php endif; ?>

		<?php if ($data['quote']): ?>
		<p><?php echo icon('fa-quote-left'); ?> <i class="text-muted"><?php echo $data['quote']; ?></i></p>
		<?php endif; ?>
	</div>
	<div class="col-md-6">
		<h4 class="no-margin"><b>Messagerie</b></h4>
		<hr />
		<?php if ($messages = $this->user->get_messages()): ?>
		<a href="<?php echo url('user/messages'); ?>" class="btn btn-info btn-block btn-lg">Vous avez <?php echo $messages > 0 ? $messages.' messages non lus !' : '1 message non lu !'; ?></a>
		<?php else: ?>
		Aucun nouveau message...
		<?php endif; ?>
	</div>
</div>