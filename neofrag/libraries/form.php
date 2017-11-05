<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Form extends Library
{
	static private $types = [
		'text',
		'password',
		'email',
		'url',
		'date',
		'datetime',
		'time',
		'number',
		'phone',
		'checkbox',
		'radio',
		'select',
		'tags',
		'file',
		'textarea',
		'editor',
		'colorpicker',
		'iconpicker',
		'legend'
	];

	private $_buttons          = [];
	private $_confirm_deletion = [];
	private $_errors           = [];
	private $_rules            = [];
	private $_values           = [];
	private $_display_required = TRUE;
	private $_fast_mode        = FALSE;
	private $_display_captcha  = FALSE;

	static private function _token($id)
	{
		static $tokens;

		if ($tokens === NULL)
		{
			$tokens = NeoFrag()->session('form') ?: [];
		}

		if (empty($tokens[$id]))
		{
			NeoFrag()->session->set('form', $id, $tokens[$id] = unique_id(array_merge([$id], $tokens)));
		}

		return $tokens[$id];
	}

	public function add_rules($rules, $values = [])
	{
		if (!is_array($rules))
		{
			$this->_values = $values;

			$rules = $this->form($rules);
		}

		foreach ($rules as $var => $options)
		{
			if (!empty($options['rules']))
			{
				$options['rules'] = explode('|', $options['rules']);
			}

			$this->_rules[$var] = $options;
		}

		return $this;
	}

	public function add_captcha()
	{
		if (!$this->user())
		{
			$this->_display_captcha = $this->captcha->is_ok();
		}

		return $this;
	}

	public function add_back($url)
	{
		array_unshift($this->_buttons, [
			'label'  => NeoFrag()->lang('back'),
			'action' => $this->session->get_back() ?: $url
		]);

		return $this;
	}

	public function add_submit($label)
	{
		$this->_buttons[] = [
			'type'  => 'submit',
			'label' => $label
		];

		return $this;
	}

	public function token($id = NULL)
	{
		if ($id === NULL)
		{
			$id = $this->id;
		}

		return self::_token($id);
	}

	public function confirm_deletion($title, $message = '')
	{
		$this->_confirm_deletion = [$title, $message];
		return $this;
	}

	public function display_required($display)
	{
		$this->_display_required = $display;
		return $this;
	}

	public function fast_mode()
	{
		$this->_fast_mode        = TRUE;
		$this->_display_required = FALSE;
		return $this;
	}

	public function is_valid(&$post = NULL)
	{
		$post = post($token = $this->token());

		if (($this->_display_captcha && !$this->captcha->is_valid()) || strtolower($_SERVER['REQUEST_METHOD']) != 'post' || (empty($post) && empty($_FILES[$token])))
		{
			return FALSE;
		}

		if ($this->_confirm_deletion)
		{
			return $post === ['delete'];
		}

		foreach ($post as $key => &$value)
		{
			if (!in_array($key, array_keys($this->_rules)))
			{
				return FALSE;
			}
			else if (is_array($value))
			{
				array_walk_recursive($value, function(&$v, $k){
					$v = utf8_htmlentities(trim($v));
				});
			}
			else if ($value !== NULL)
			{
				$value = utf8_htmlentities(trim($value));
			}

			unset($value);
		}

		foreach ($this->_rules as $var => $options)
		{
			if (!is_array($options) || !isset($options['type']) || !in_array($type = $options['type'], self::$types) || !method_exists($this, '_check_'.$type))
			{
				$type = 'text';
			}

			if (($error = $this->{'_check_'.$type}($post, $var, $options)) !== TRUE)
			{
				$this->_errors[$var] = $error;
			}
		}

		if (empty($this->_errors))
		{
			if ($this->_has_upload())
			{
				$files = $_FILES[$token];

				foreach ($this->_rules as $var => $options)
				{
					if (isset($options['type']) && $options['type'] == 'file')
					{
						if (!empty($post[$var]) && $post[$var] == 'delete' && !empty($options['value']))
						{
							$this->file->delete($options['value']);
							$options['value'] = $post[$var] = 0;
						}

						if (!empty($files['tmp_name'][$var]))
						{
							if (!($post[$var] = $this->file->upload($files, isset($options['upload']) ? $options['upload'] : NULL, $filename, isset($options['value']) ? $options['value'] : NULL, $var)))
							{
								$this->_errors[$var] = NeoFrag()->lang('file_transfer_error');
								return FALSE;
							}
							else if (isset($options['post_upload']) && is_callable($options['post_upload']))
							{
								call_user_func_array($options['post_upload'], [$filename]);
							}
						}

						if (!empty($options['value']) && empty($post[$var]))
						{
							$post[$var] = $options['value'];
						}
					}
				}
			}

			return TRUE;
		}

		return FALSE;
	}

	public function get_errors()
	{
		return $this->_errors;
	}

	public function value($var)
	{
		return isset($this->_values[$var]) ? $this->_values[$var] : NULL;
	}

	private function _check_text($post, $var, $options)
	{
		if (!empty($options['rules']) && in_array('disabled', $options['rules']))
		{
			return TRUE;
		}

		if (!in_array($post[$var], ['', NULL]) &&
			!empty($options['values']) &&
			is_array($options['values']) &&
			is_array($post[$var]) &&
			array_diff(array_filter($post[$var]), array_map('utf8_htmlentities', array_keys($options['values'])))
		)
		{
			return NeoFrag()->lang('invalid_values', count($post[$var]));
		}

		$is_file = !empty($options['type']) && $options['type'] == 'file';

		if (	!empty($options['rules']) &&
				in_array('required', $options['rules']) &&
				(
					($is_file && empty($_FILES[$this->token()]['tmp_name'][$var])) ||
					(!$is_file && in_array($post[$var], ['', NULL]))
				)
			)
		{
			return NeoFrag()->lang('required_input');
		}

		if ($is_file && !empty($_FILES[$this->token()]['error'][$var]) && $_FILES[$this->token()]['error'][$var] != 4)
		{
			return NeoFrag()->lang('file_transfer_error_'.$_FILES[$this->token()]['error'][$var]);
		}

		if (isset($options['check']) && is_callable($options['check']))
		{
			if (!empty($options['type']) && $options['type'] == 'file')
			{
				$error = !empty($_FILES[$this->token()]['tmp_name'][$var]) ? call_user_func_array($options['check'], [$_FILES[$this->token()]['tmp_name'][$var], extension($_FILES[$this->token()]['name'][$var])]) : TRUE;
			}
			else
			{
				$error = call_user_func_array($options['check'], [$post[$var], $post]);
			}

			if (!in_array($error, [TRUE, NULL], TRUE))
			{
				return $error;
			}
		}

		return TRUE;
	}

	private function _check_file(&$post, $var, $options)
	{
		if (empty($post[$var]))
		{
			$post[$var] = NULL;
		}

		return $this->_check_text($post, $var, $options);
	}

	private function _check_checkbox(&$post, $var, $options)
	{
		$post[$var] = array_filter(isset($post[$var]) ? $post[$var] : [], function($a){
			return strlen($a);
		});
		return $this->_check_text($post, $var, $options);
	}

	private function _check_email($post, $var, $options)
	{
		if ($post[$var] !== '' && !is_valid_email($post[$var]))
		{
			return NeoFrag()->lang('wrong_email');
		}

		return $this->_check_text($post, $var, $options);
	}

	private function _check_url($post, $var, $options)
	{
		if ($post[$var] !== '' && !is_valid_url($post[$var]))
		{
			return NeoFrag()->lang('wrong_url');
		}

		return $this->_check_text($post, $var, $options);
	}

	private function _check_number(&$post, $var, $options)
	{
		if ($post[$var] !== '' && $post[$var] != (int)$post[$var])
		{
			return 'Nombre invalide';
		}

		return $this->_check_text($post, $var, $options);
	}

	private function _check_phone(&$post, $var, $options)
	{
		if ($post[$var] !== '' && !preg_match('/^0[1-9]([. ]?)\d{2}(?:\1\d{2}){3}$/', $post[$var], $match))
		{
			return 'Numéro de téléphone invalide';
		}

		return $this->_check_text($post, $var, $options);
	}

	private function _check_datetime(&$post, $var, $options)
	{
		datetime2sql($post[$var]);
		return $this->_check_text($post, $var, $options);
	}

	private function _check_time(&$post, $var, $options)
	{
		time2sql($post[$var]);
		return $this->_check_text($post, $var, $options);
	}

	private function _check_date(&$post, $var, $options)
	{
		date2sql($post[$var]);
		return $this->_check_text($post, $var, $options);
	}

	private function _check_editor(&$post, $var, $options)
	{
		$post[$var] = trim(preg_replace('/ {2,}/', ' ', preg_replace('/(^ +?)|( +?$)/m', '', str_replace('&nbsp;', ' ', $post[$var]))));
		return $this->_check_text($post, $var, $options);
	}

	public function display()
	{
		if ($this->_confirm_deletion)
		{
			list($title, $message) = $this->_confirm_deletion;

			if ($this->url->ajax())
			{
				return '<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">'.NeoFrag()->lang('close').'</span></button>
							<h4 class="modal-title">'.$title.'</h4>
						</div>
						<div class="modal-body">
							'.$message.'
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">'.NeoFrag()->lang('cancel').'</button>
							<a class="btn btn-danger delete-confirm" href="'.url($this->url->request).'" data-form-id="'.$this->token().'" onclick="return confirm_deletion(this);">'.NeoFrag()->lang('remove').'</a>
						</div>';
			}
			else
			{
				//TODO
				/*return 	'<p>'.$message.'</p><p>
							<button type="button" class="btn btn-default" onclick="$(this).parents(\'.alert\').alert(\'close\');">Annuler</button>
							<a class="btn btn-danger delete-confirm" href="'.url($this->url->request).'" data-form-id="'.$this->token().'" onclick="return confirm_deletion(this);">Supprimer</a>
						</p>';*/
			}

			return;
		}

		$output = '';

		if ($has_upload = $this->_has_upload())
		{
			$this->js('neofrag.file');
		}

		$output .= '<form'.(!$this->_fast_mode ? ' class="form-horizontal"' : '').' action="'.url($this->url->request).'" method="post"'.($has_upload ? ' enctype="multipart/form-data"' : '').'>
						<fieldset>';

		$post = post($this->token());

		foreach ($this->_rules as $var => $options)
		{
			if (!is_array($options) || !isset($options['type']) || !in_array($type = $options['type'], self::$types))
			{
				$type = 'text';
			}

			if ($display = $this->{'_display_'.$type}($var, $options, isset($post[$var]) ? $post[$var] : NULL))
			{
				$output .= '<div class="form-group'.(isset($this->_errors[$var]) ? ' has-error' : '').(isset($options['type']) && $options['type'] == 'legend' ? ' legend' : '').'">';

				if ($this->_fast_mode || $type == 'legend')
				{
					$output .= $display;
				}
				else
				{
					$output .= '<label class="control-label col-md-3"'.(!in_array($type, ['radio', 'checkbox']) ? ' for="form_'.$this->token().'_'.$var.'"' : '').$this->_display_popover($var, $options, $icons).'>'.$icons.' '.(!empty($options['label']) ? $this->lang($options['label'], NULL) : '');

					if (isset($options['rules']) && in_array('required', $options['rules']) && $this->_display_required)
					{
						$output .= '<em>*</em>';
					}

					$output .= '</label><div class="'.(!empty($options['size']) && preg_match('/^col-md-([1-9])$/', $options['size'], $match) ? 'col-md-'.$match[1] : 'col-md-9').'">'.$display.'</div>';
				}

				$output .= '</div>';
			}
		}

		if ($this->_display_captcha)
		{
			NeoFrag()->js('https://www.google.com/recaptcha/api.js?hl='.$this->config->lang.'&_=');
			$output .= '<div class="form-group"><div class="'.($this->_fast_mode ? 'input-group' : 'col-md-offset-3 col-md-9').'">'.$this->captcha->display().'</div></div>';
		}

		if ($this->_display_required)
		{
			$output .= '<div class="form-group"><div class="col-md-offset-3 col-md-9"><em class="text-muted">'.NeoFrag()->lang('required_fields').'</em></div></div>';
		}

		if (!empty($this->_buttons))
		{
			$output .= '<div class="'.($this->_fast_mode ? 'text-center' : 'form-group').'">';

			if (!$this->_fast_mode)
			{
				$output .= '<div class="col-md-offset-3 col-md-9">';
			}

			foreach ($this->_buttons as $i => $button)
			{
				if ($i > 0)
				{
					$output .= ' ';
				}

				$output .= $this->_display_button($button);
			}

			if (!$this->_fast_mode)
			{
				$output .= '</div>';
			}

			$output .= '</div>';
		}

		$output .= '</fieldset>
				</form>';

		$this->reset();

		return $output;
	}

	private function _display_button($button)
	{
		if (isset($button['type']) && $button['type'] == 'submit')
		{
			return '<button class="btn btn-primary" type="submit">'.$button['label'].'</button>';
		}
		else if (!empty($button['label']) && !empty($button['action']))
		{
			return '<a href="'.url($button['action']).'" class="btn btn-default">'.$button['label'].'</a>';
		}

		return '';
	}

	private function _display_value($var, $options)
	{
		$post = post();

		if (isset($post[$this->token()][$var]))
		{
			if (is_array($post[$this->token()][$var]))
			{
				return array_values(array_filter($post[$this->token()][$var]));
			}
			else
			{
				return utf8_htmlentities(trim($post[$this->token()][$var]));
			}
		}
		else if (isset($options['checked']))
		{
			return array_keys(array_filter($options['checked']));
		}
		else if (isset($options['value']))
		{
			return (string)$options['value'];
		}

		return isset($options['default']) ? (string)$options['default'] : '';
	}

	private function _display_popover($var, $options, &$icons = '')
	{
		$popover = $icons = [];

		if (!empty($options['description']))
		{
			$popover[] = ($icons[] = '<span class="text-info">'.icon('fa-info-circle').'</span>').' '.$this->lang($options['description'], NULL);
		}

		if (!empty($this->_errors[$var]))
		{
			$popover[] = ($icons[] = '<span class="text-danger">'.icon('fa-exclamation-triangle').'</span>').' <span class="text-danger">'.$this->_errors[$var].'</span>';
		}

		$icons = implode(' ', $icons);

		if ($popover)
		{
			return ' data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="'.utf8_htmlentities(implode('<br /><br />', $popover)).'"';
		}
	}

	private function _display_text($var, $options, $post, $type = 'text')
	{
		$classes = [];

		if (in_array($type, ['date', 'datetime', 'time']))
		{
			$types = ['date' => 'L', 'datetime' => 'L LT', 'time' => 'LT'];

			NeoFrag()	->css('bootstrap-datetimepicker.min')
								->js('bootstrap-datetimepicker/moment.min')
								->js('bootstrap-datetimepicker/bootstrap-datetimepicker.min')
								->js('bootstrap-datetimepicker/locales/'.$this->config->lang)
								->js_load('$(".input-group.'.$type.'").datetimepicker({allowInputToggle: true, locale: "'.$this->config->lang.'", format: "'.$types[$type].'"});');

			$classes[] = $type;

			if (empty($options['icon']))
			{
				$options['icon'] = $type == 'time' ? 'fa-clock-o' : 'fa-calendar';
			}

			$type = 'text';
		}
		else if ($type == 'email')
		{
			$type = 'text';

			if (empty($options['icon']))
			{
				$options['icon'] = 'fa-envelope-o';
			}
		}
		else if ($type == 'url')
		{
			$type = 'text';

			if (empty($options['icon']))
			{
				$options['icon'] = 'fa-globe';
			}
		}
		else if ($type == 'phone')
		{
			$type = 'text';

			if (empty($options['icon']))
			{
				$options['icon'] = 'fa-phone';
			}
		}
		else if ($type == 'colorpicker')
		{
			$type = 'text';

			$classes[] = 'color';

			$options['icon'] = FALSE;

			NeoFrag()	->css('bootstrap-colorpicker.min')
								->js('bootstrap-colorpicker.min')
								->js_load('$(".input-group.color").colorpicker({format: "hex", component: ".input-group-addon,input", colorSelectors: '.json_encode(get_colors()).'});');
		}

		$output = '';

		if (isset($options['icon']))
		{
			$output .= '<div class="input-group'.(!empty($classes) ? ' '.implode(' ', $classes) : '').'">
				<span class="input-group-addon">'.($options['icon'] ? icon($options['icon']) : '<i></i>').'</span>';
		}

		$placeholder = '';

		if ($type != 'file')
		{
			$class = ' class="form-control"';
			$value = ' value="'.addcslashes($this->_display_value($var, $options), '"').'"';

			if (!empty($options['placeholder']))
			{
				$placeholder = $options['placeholder'];
			}
			else if ($this->_fast_mode && !empty($options['label']))
			{
				$placeholder = $options['label'];
			}

			if ($placeholder)
			{
				$placeholder = ' placeholder="'.$this->lang($placeholder, NULL).'"';
			}
		}

		$input = '<input id="form_'.$this->token().'_'.$var.'" name="'.$this->token().'['.$var.']" type="'.$type.'"'.(!empty($value) ? $value : '').(!empty($class) ? $class : '').($type == 'password' && isset($options['autocomplete']) && $options['autocomplete'] === FALSE ? ' autocomplete="off"' : '').(!empty($options['rules']) && in_array('disabled', $options['rules']) ? ' disabled="disabled"' : '').$placeholder.' />';

		if ($type == 'file')
		{
			$post = post();

			$input = '<div style="margin: 7px 0;"><p>'.icon('fa-download').' '.NeoFrag()->lang('upload_file').(!empty($options['info']) ? $options['info'] : '').'</p>'.$input.'</div>';

			if (!empty($options['value']))
			{
				if (isset($post[$this->token()][$var]) && $post[$this->token()][$var] == 'delete')
				{
					$input = '<input type="hidden" name="'.$this->token().'['.$var.']" value="delete" />'.$input;
				}
				else
				{
					$input = '	<div class="row">
									<div class="col-md-3">
										<div class="thumbnail no-margin">
											<img src="'.url($this->db->select('path')->from('nf_files')->where('file_id', $options['value'])->row()).'" alt="" />
											<div class="caption text-center">
												<a class="btn btn-outline btn-danger btn-xs form-file-delete" href="#" data-input="'.$this->token().'['.$var.']">'.icon('fa-trash-o').' '.NeoFrag()->lang('remove').'</a>
											</div>
										</div>
									</div>
									<div class="col-md-9">
										'.$input.'
									</div>
								</div>';
				}
			}
		}

		$output .= $input;

		if (isset($options['icon']))
		{
			if (in_array('color', $classes))
			{
				$output .= '<span class="input-group-addon"><span class="fa fa-eyedropper"></span></span>';
			}

			$output .= '</div>';
		}

		return $output;
	}

	private function _display_iconpicker($var, $options, $post)
	{
		NeoFrag()	->css('bootstrap-iconpicker.min')
							->js('bootstrap-iconpicker-iconset-fontawesome-4.3.0.min')
							->js('bootstrap-iconpicker.min')
							->js_load('	$(".btn.iconpicker").iconpicker({
											arrowClass: "btn-danger",
											arrowPrevIconClass: "glyphicon glyphicon-chevron-left",
											arrowNextIconClass: "glyphicon glyphicon-chevron-right",
											cols: 10,
											rows: 5,
											iconset: "fontawesome",
											labelHeader: "'.NeoFrag()->lang('pages').'",
											labelFooter: "<div class=\"pull-right\">'.NeoFrag()->lang('icons').'</div>",
											searchText: "'.NeoFrag()->lang('search...').'",
											selectedClass: "btn-primary",
											unselectedClass: ""
										});');

		return '<button id="form_'.$this->token().'_'.$var.'" name="'.$this->token().'['.$var.']" class="btn btn-default'.((isset($this->_errors[$var])) ? ' btn-danger' : '').' iconpicker" data-icon="'.addcslashes($this->_display_value($var, $options), '"').'"></button>';
	}

	private function _display_colorpicker($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'colorpicker');
	}

	private function _display_password($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'password');
	}

	private function _display_date($var, $options, $post)
	{
		if (isset($options['value']) && $options['value'] !== '')
		{
			$options['value'] = timetostr(NeoFrag()->lang('date_short'), $options['value']);
		}
		else
		{
			$options['value'] = '';
		}

		return $this->_display_text($var, $options, $post, 'date');
	}

	private function _display_datetime($var, $options, $post)
	{
		if (isset($options['value']) && $options['value'] !== '')
		{
			$options['value'] = timetostr(NeoFrag()->lang('date_time_short'), $options['value']);
		}
		else
		{
			$options['value'] = '';
		}

		return $this->_display_text($var, $options, $post, 'datetime');
	}

	private function _display_time($var, $options, $post)
	{
		if (isset($options['value']) && $options['value'] !== '' && $options['value'] !== '00:00:00')
		{
			$options['value'] = timetostr(NeoFrag()->lang('time_short'), $options['value']);
		}
		else
		{
			$options['value'] = '';
		}

		return $this->_display_text($var, $options, $post, 'time');
	}

	private function _display_number($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'number');
	}

	private function _display_phone($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'phone');
	}

	private function _display_email($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'email');
	}

	private function _display_url($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'url');
	}

	private function _display_file($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'file');
	}

	private function _display_tags($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'tags');
	}

	private function _display_checkbox($var, $options, $post)
	{
		$output = '<input type="hidden" name="'.$this->token().'['.$var.'][]" value="" />';

		if (!empty($options['values']))
		{
			$user_value = (array)$this->_display_value($var, $options);

			foreach ($options['values'] as $value => $label)
			{
				 $output .= '	<div class="checkbox">
									<label>
										<input type="checkbox" name="'.$this->token().'['.$var.'][]" value="'.$value.'"'.(in_array((string)$value, $user_value) ? ' checked="checked"' : '').' />
										'.$this->lang($label, NULL).'
									</label>
								</div>';
			}
		}

		return $output;
	}

	private function _display_radio($var, $options, $post)
	{
		$output = '<input type="hidden" name="'.$this->token().'['.$var.']" value="" />';

		if (!empty($options['values']))
		{
			$user_value = $this->_display_value($var, $options);

			foreach ($options['values'] as $value => $label)
			{
				 $output .= '	<label class="radio-inline">
									<input type="radio" name="'.$this->token().'['.$var.']" value="'.$value.'"'.($user_value == (string)$value ? ' checked="checked"' : '').' />
									'.$this->lang($label, NULL).'
								</label>';
			}
		}

		return $output;
	}

	private function _display_select($var, $options, $post)
	{
		if (empty($options['values']) && (!isset($options['rules']) || !in_array('required', $options['rules'])))
		{
			return;
		}

		$output = '<select class="form-control" id="form_'.$this->token().'_'.$var.'" name="'.$this->token().'['.$var.']">
						<option></option>';

		if (!empty($options['values']))
		{
			$user_value = $this->_display_value($var, $options);

			foreach ($options['values'] as $value => $label)
			{
				$output .= '<option value="'.$value.'"'.($user_value == (string)$value ? ' selected="selected"' : '').'>'.$this->lang($label, NULL).'</option>';
			}
		}

		return $output.'</select>';
	}

	private function _display_textarea($var, $options, $post, $editor = FALSE)
	{
		return '<textarea id="form_'.$this->token().'_'.$var.'" class="form-control'.($editor ? ' editor' : '').'" rows="10" name="'.$this->token().'['.$var.']">'.$this->_display_value($var, $options).'</textarea>';
	}

	private function _display_editor($var, $options, $post)
	{
		$this	->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});');

		return $this->_display_textarea($var, $options, $post, TRUE);
	}

	private function _display_legend($var, $options, $post)
	{
		return $output = '<div class="form-legend">'.(!empty($options['label']) ? $this->lang($options['label'], NULL) : '').'</div>';
	}

	private function _has_upload()
	{
		foreach ($this->_rules as $var => $options)
		{
			if (isset($options['type']) && $options['type'] == 'file')
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}
