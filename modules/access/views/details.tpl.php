<div class="table-responsive">
	<table class="table table-access table-hover">
		<thead>
			<tr>
				<th class="col-md-6 col-lg-7"><?php echo $this->lang('Groupes') ?></th>
				<th class="col-md-1 text-center" data-radio="success">
					<div data-toggle="tooltip" title="<?php echo $this->lang('Groupe autorisé') ?>"><?php echo icon('fa-check') ?></div>
				</th>
				<th class="col-md-1 text-center" data-radio="danger">
					<div data-toggle="tooltip" title="<?php echo $this->lang('Groupe exclu') ?>"><?php echo icon('fa-ban') ?></div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($groups as $group_id => $active): ?>
			<tr data-group="<?php echo $group_id ?>">
				<td><?php echo $this->groups->display($group_id) ?></td>
				<?php echo $this->view('radio', ['class' => 'success', 'active' => $active]) ?>
				<?php if ($group_id == 'admins') echo '<td></td>'; else echo $this->view('radio', ['class' => 'danger', 'active' => !$active]) ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
