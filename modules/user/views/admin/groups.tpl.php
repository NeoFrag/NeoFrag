<?php $groups = $this->groups(); if (!empty($groups)): ?>
	<form action="<?php echo url($this->url->request) ?>" method="post">
		<ul class="groups">
		<?php foreach ($groups as $group_id => $group): ?>
			<?php if ($group['users'] === NULL) continue ?>
			<li class="col-12">
				<label>
					<input type="checkbox" name="<?php echo $form_id ?>[groups][]" value="<?php echo $group_id ?>"<?php if (in_array($user_id, $group['users'])) echo ' checked="checked"'; if ($group['auto'] && $group['auto'] != 'neofrag') echo ' disabled="disabled"' ?> />
					<?php echo $this->groups->display($group_id, TRUE, FALSE) ?>
				</label>
			</li>
		<?php endforeach ?>
		</ul>
		<div class="text-center">
			<button style="margin-top: 15px;" class="btn btn-outline btn-primary"><?php echo icon('fas fa-check').' '.$this->lang('Valider') ?></button>
		</div>
	</form>
<?php endif ?>
