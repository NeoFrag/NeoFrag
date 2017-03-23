<div class="panel-group" id="accordion" role="tablist">
	<?php foreach ($this->groups() as $group_id => $group): if (empty($group['users']) || !($users = array_intersect_key($data['users'], array_flip($group['users'])))) continue; ?>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab">
				<h4 class="panel-title">
					<input type="checkbox" />
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $id = url_title($group['url']); ?>">
						<?php echo $this->groups->display($group_id, TRUE, FALSE); ?>
					</a>
				</h4>
			</div>
			<div id="<?php echo $id; ?>" class="panel-collapse collapse" role="tabpanel">
				<div class="panel-body">
					<?php foreach ($users as $user_id => $username): ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="<?php echo $data['form_id']; ?>[users][]" value="<?php echo $user_id; ?>" /> <?php echo $username; ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>