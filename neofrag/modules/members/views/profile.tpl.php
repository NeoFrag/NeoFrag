<?php if (0 && $NeoFrag->user()): //TODO link compose ?>
<div class="pull-right">
	<a class="btn btn-default" href="<?php echo url('user/compose.html'); ?>"><?php echo icon('fa-envelope-o'); ?><span class="hidden-xs"> Contacter</span></a>
</div>
<?php endif; ?>
<?php echo $loader->view('profile_mini', $data); ?>
<br />
<table class="table">
	<thead>
		<tr>
			<th colspan="2"><h4 class="no-margin text-uppercase"><b>Profil du membre</b></h4></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-sign-in'); ?> Inscription</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo time_span($data['registration_date']); ?></td>
		</tr>
		<?php if (!empty($data['last_activity_date']) && $data['last_activity_date'] != '0000-00-00 00:00:00'): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-history'); ?> Dernière activité</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo time_span($data['last_activity_date']); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['date_of_birth']) && $data['date_of_birth'] != '0000-00-00'): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-birthday-cake'); ?> Date de naissance</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo timetostr($NeoFrag->lang('date_short'), $data['date_of_birth']); ?> (<?php echo date_diff(date_create($data['date_of_birth']), date_create('today'))->y; ?> ans)</td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['sex'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-'.$data['sex']); ?> Sexe</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $data['sex'] == 'male' ? 'Homme' : 'Femme'; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['location'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-map-marker'); ?> Localisation</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $data['location']; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['website'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-globe'); ?> Site web</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo strtolink($data['website']); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['quote'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-bookmark'); ?> Citation</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $data['quote']; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['signature'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-pencil'); ?> Signature</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo bbcode($data['signature']); ?></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>