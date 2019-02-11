<input type="hidden" name="module" value="<?php echo $module ?>" />
<input type="hidden" name="type" value="<?php echo $type ?>" />
<input type="hidden" name="id" value="<?php echo $id ?>" />
<?php foreach ($access as $category): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="col-8"><?php echo icon($category['icon']).' '.$category['title'] ?></th>
				<th class="col-8 col-sm-4" colspan="2"><?php echo icon('fas fa-key').' '.$this->lang('Accès') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($category['access'] as $name => $access): ?>
			<tr data-action="<?php echo $name ?>">
				<td class="text-primary"><?php echo icon($access['icon']).' '.$access['title'] ?></td>
				<td class="access-count">
					<?php echo NeoFrag()->access->count($module, $name, $id) ?>
				</td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endforeach ?>
