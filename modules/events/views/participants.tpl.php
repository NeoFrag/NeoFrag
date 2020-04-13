<div class="accordion">
	<ul class="list-group">
		<?php foreach ($this->groups() as $group_id => $group): if (empty($group['users']) || !($list_users = array_intersect_key($users, array_flip($group['users'])))) continue ?>
		<li class="list-group-item">
			<input type="checkbox" name="select-all" class="mr-2" /><a href="#" data-toggle="collapse" data-target="#<?php echo $id = url_title($group['url']) ?>"> <?php echo $this->groups->display($group_id, TRUE, FALSE) ?></a>
			<div id="<?php echo $id ?>" class="collapse ml-4 mt-3">
				<?php foreach ($list_users as $user_id => $username): ?>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="<?php echo $form_id ?>[users][]" value="<?php echo $user_id ?>" /> <?php echo $username ?>
					</label>
				</div>
				<?php endforeach ?>
			</div>
		</li>
		<?php endforeach ?>
	</ul>
</div>
