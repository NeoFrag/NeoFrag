<?php if (isset($data['module_actions']) && count($data['module_actions']) > 3): ?>
	<div class="pull-right btn-group">
		<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo $this->lang('actions'); ?> <span class="caret"></span></button>
		<ul class="dropdown-menu">
		<?php
			foreach ($data['module_actions'] as $action)
			{
				list($url, $title, $icon) = $action;

				echo '<li><a href="'.url($url).'">'.$icon.' '.$title.'</a></li>';
			}
		?>
		</ul>
	</div>
<?php elseif (!empty($data['module_actions'])): ?>
	<div class="pull-right">
		<?php
			$output = '';

			foreach ($data['module_actions'] as $action)
			{
				list($url, $title, $icon) = $action;

				$output .= '<a class="btn btn-link" href="'.url($url).'">'.icon($icon).' '.$title.'</a> ';
			}

			echo trim($output);
		?>
	</div>
<?php endif; ?>