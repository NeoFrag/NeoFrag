<div class="table-responsive">
	<table class="table table-access table-hover">
		<thead>
			<tr>
				<th class="col-md-6 col-lg-7"><?php echo i18n('groups'); ?></th>
				<th class="col-md-1 text-center" data-radio="success">
					<div data-toggle="tooltip" title="<?php echo i18n('authorized_group'); ?>"><?php echo icon('fa-check'); ?></div>
				</th>
				<th class="col-md-1 text-center" data-radio="danger">
					<div data-toggle="tooltip" title="<?php echo i18n('forbidden_group'); ?>"><?php echo icon('fa-ban'); ?></div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data['groups'] as $group_id => $active): ?>
			<tr data-group="<?php echo $group_id; ?>">
				<td><?php echo $NeoFrag->groups->display($group_id); ?></td>
				<?php echo $loader->load->view('radio', array('class' => 'success', 'active' => $active)); ?>
				<?php if ($group_id == 'admins') echo '<td></td>'; else echo $loader->load->view('radio', array('class' => 'danger', 'active' => !$active)); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>