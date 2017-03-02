<?php $groups = $this->groups(); if (!empty($groups)): ?>
	<form action="<?php echo url($this->url->request); ?>" method="post">
		<ul class="groups">
		<?php foreach ($groups as $group_id => $group): ?>
			<?php if ($group['users'] === NULL) continue; ?>
			<li class="col-md-4">
				<label>
					<input type="checkbox" name="<?php echo $data['form_id']; ?>[groups][]" value="<?php echo $group_id; ?>"<?php if (in_array($data['user_id'], $group['users'])) echo ' checked="checked"'; if ($group['auto'] && $group['auto'] != 'neofrag') echo ' disabled="disabled"'; ?> />
					<?php echo $this->groups->display($group_id, TRUE, FALSE); ?>
				</label>
			</li>
		<?php endforeach; ?>
		</ul>
		<script type="text/javascript">
			$(function(){
				$('ul.groups input[type=checkbox]').change(function(){
					if ($(this).prop('value') == 'admins' || $(this).prop('value') == 'members'){
						other = $(this).prop('value') == 'admins' ? $('ul.groups input[type=checkbox][value=members]') : $('ul.groups input[type=checkbox][value=admins]');
						
						if ($(this).prop('checked')){
							other.prop('checked', false);
						}
						else{
							other.prop('checked', 'checked');
						}
					}
				});
			});
		</script>
		<div class="text-center">
			<button style="margin-top: 15px;" class="btn btn-outline btn-primary"><?php echo icon('fa-check').' '.$this->lang('save'); ?></button>
		</div>
	</form>
<?php endif; ?>
