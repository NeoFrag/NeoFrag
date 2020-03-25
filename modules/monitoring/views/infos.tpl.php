<div class="row">
	<?php foreach ($check as $check): ?>
		<div class="col-12 col-lg-6">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>
							<?php echo icon($check['icon']).' '.$check['title'] ?>
							<?php if (isset($check['value'])): ?><span class="float-right"><?php echo $check['value'] ?></span><?php endif ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($check['check'] as $name => $check): ?>
					<tr>
						<td id="<?php echo 'server-'.$name ?>"><?php echo icon('fas fa-spinner fa-spin').' <span>'.$check['title'].'</span>' ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php endforeach ?>
</div>
