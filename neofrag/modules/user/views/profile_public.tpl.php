<?php if ($this->user()): ?>
	<?php if ($this->user('user_id') == $data['user_id']): ?>
	<div class="pull-right">
		<a class="btn btn-default" href="<?php echo url('user'); ?>"><?php echo icon('fa-cogs'); ?><span class="hidden-xs"> <?php echo $this->lang('manage_my_account'); ?></span></a>
	</div>
	<?php else: ?>
	<div class="pull-right">
		<a class="btn btn-default" href="<?php echo url('user/messages/compose/'.$data['user_id'].'/'.url_title($data['username'])); ?>"><?php echo icon('fa-envelope-o'); ?><span class="hidden-xs"> <?php echo $this->lang('send_pm'); ?></span></a>
	</div>
	<?php endif; ?>
<?php endif; ?>
<?php echo $this->view('profile_mini', $data); ?>
<br />
<table class="table">
	<thead>
		<tr>
			<th colspan="2"><h4 class="no-margin text-uppercase"><b><?php echo $this->lang('member_profile_title'); ?></b></h4></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-sign-in  fa-rotate-90').' '.$this->lang('registration_date'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo time_span($data['registration_date']); ?></td>
		</tr>
		<?php if (!empty($data['last_activity_date']) && $data['last_activity_date'] != NULL): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-history').' '.$this->lang('last_activity'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo time_span($data['last_activity_date']); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['date_of_birth'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-birthday-cake').' '.$this->lang('birth_date'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo timetostr($this->lang('date_short'), $data['date_of_birth']).' '.$this->lang('age', $age = date_diff(date_create($data['date_of_birth']), date_create('today'))->y, $age); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['sex'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-'.$data['sex']).' '.$this->lang('gender'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $this->lang($data['sex'] == 'male' ? 'male' : 'female'); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['location'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-map-marker').' '.$this->lang('location'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $data['location']; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['website'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-globe').' '.$this->lang('website'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo strtolink($data['website']); ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['quote'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-bookmark').' '.$this->lang('quote'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo $data['quote']; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($data['signature'])): ?>
		<tr>
			<td class="col-lg-3 col-md-4 col-xs-5"><b><?php echo icon('fa-pencil').' '.$this->lang('signature'); ?></b></td>
			<td class="col-lg-9 col-md-8 col-xs-7"><?php echo bbcode($data['signature']); ?></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>