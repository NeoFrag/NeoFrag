<div id="modal-phpinfo" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $this->lang('close'); ?>"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Informations détaillée</h4>
			</div>
			<div class="modal-body">
				<?php echo display($data['phpinfo']); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<?php foreach ($data['check'] as $check): ?>
		<div class="col-md-12 col-lg-6">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>
							<?php echo icon($check['icon']).' '.$check['title']; ?>
							<?php if (isset($check['value'])): ?><span class="pull-right"><?php echo $check['value']; ?></span><?php endif; ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($check['check'] as $name => $check): ?>
					<tr>
						<td id="<?php echo 'server-'.$name; ?>"><?php echo icon('fa-spinner fa-spin').' <span>'.$check['title'].'</span>'; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endforeach; ?>
</div>
