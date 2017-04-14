<?php if ($this->user()): ?>
	<?php if ($this->user->id == $user_id): ?>
	<div class="pull-right">
		<a class="btn btn-default" href="<?php echo url('user') ?>"><?php echo icon('fa-cogs') ?><span class="hidden-xs"> <?php echo $this->lang('Gérer mon compte') ?></span></a>
	</div>
	<?php else: ?>
	<div class="pull-right">
		<a class="btn btn-default" href="<?php echo url('user/messages/compose/'.$user_id.'/'.url_title($username)) ?>"><?php echo icon('fa-envelope-o') ?><span class="hidden-xs"> <?php echo $this->lang('Contacter') ?></span></a>
	</div>
	<?php endif ?>
<?php endif ?>
<?php echo $this->view('profile_mini', $data) ?>
<br />
<table class="table">
	<thead>
		<tr>
			<th colspan="2"><h4 class="m-0 text-uppercase"><b><?php echo $this->lang('Profil du membre') ?></b></h4></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-sign-in  fa-rotate-90').' '.$this->lang('Inscrit depuis le') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo time_span($registration_date) ?></td>
		</tr>
		<?php if (!empty($last_activity_date) && $last_activity_date != NULL): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-history').' '.$this->lang('Dernière activité') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo time_span($last_activity_date) ?></td>
		</tr>
		<?php endif ?>
		<?php if (!empty($date_of_birth)): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-birthday-cake').' '.$this->lang('Date de naissance') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo timetostr($this->lang('%d/%m/%Y'), $date_of_birth).' '.$this->lang('(%d an)|(%d ans)', $age = date_diff(date_create($date_of_birth), date_create('today'))->y, $age) ?></td>
		</tr>
		<?php endif ?>
		<?php if (!empty($sex)): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-'.$sex).' '.$this->lang('Sexe') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo $this->lang($sex == 'male' ? 'Homme' : 'Femme') ?></td>
		</tr>
		<?php endif ?>
		<?php if (!empty($location)): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-map-marker').' '.$this->lang('Localisation') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo $location ?></td>
		</tr>
		<?php endif ?>
		<?php if (!empty($website)): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-globe').' '.$this->lang('Site web') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo strtolink($website) ?></td>
		</tr>
		<?php endif ?>
		<?php if (!empty($quote)): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-bookmark').' '.$this->lang('Citation') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo $quote ?></td>
		</tr>
		<?php endif ?>
		<?php if (!empty($signature)): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-5"><b><?php echo icon('fa-pencil').' '.$this->lang('Signature') ?></b></td>
			<td class="col-lg-9 col-md-8 col-7"><?php echo bbcode($signature) ?></td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
