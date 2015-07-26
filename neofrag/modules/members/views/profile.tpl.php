<?php if (0 && $NeoFrag->user()): //TODO link compose ?>
<div class="pull-right">
	<a class="btn btn-default" href="{base_url}user/compose.html"><i class="fa fa-envelope-o"></i><span class="hidden-xs"> Contacter</span></a>
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
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-sign-in"></i> Inscription</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo time_span($data['registration_date']); ?></td>
		</tr>
		<?php if (!empty($data['last_activity_date']) && $data['last_activity_date'] != '0000-00-00 00:00:00'): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-history"></i> Dernière activité</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo time_span($data['last_activity_date']); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['date_of_birth']) && $data['date_of_birth'] != '0000-00-00'): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-birthday-cake"></i> Date de naissance</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo timetostr($NeoFrag->lang('date_short'), $data['date_of_birth']); ?> (<?php echo date_diff(date_create($data['date_of_birth']), date_create('today'))->y; ?> ans)</td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['sex'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-<?php echo $data['sex']; ?>"></i> Sexe</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $data['sex'] == 'male' ? 'Homme' : 'Femme'; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['location'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-map-marker"></i> Localisation</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7">{location}</td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['website'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-globe"></i> Site web</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo strtolink($data['website']); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['quote'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-bookmark"></i> Citation</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7">{quote}</td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['signature'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><i class="fa fa-pencil"></i> Signature</b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo bbcode($data['signature']); ?></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>