<div id="accordion" class="accordion">
	<?php foreach ($this->groups() as $group_id => $group): if (empty($group['users']) || !($list_users = array_intersect_key($users, array_flip($group['users'])))) continue ?>
		<div class="card">
			<div class="card-header" data-toggle="collapse" data-target="#<?php echo $id = url_title($group['url']) ?>">
				<h5 class="card-title">
					<input type="checkbox" />
					<?php echo $this->groups->display($group_id, TRUE, FALSE) ?>
				</h5>
			</div>
			<div id="<?php echo $id ?>" class="collapse">
				<div class="card-body">
					<?php foreach ($list_users as $user_id => $username): ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="<?php echo $form_id ?>[users][]" value="<?php echo $user_id ?>" /> <?php echo $username ?>
							</label>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
