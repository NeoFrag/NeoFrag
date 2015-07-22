<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Form extends Library
{
	static private $types      = array('text', 'password', 'email', 'url', 'date', 'checkbox', 'radio', 'select', 'tags', 'file', 'textarea', 'editor', 'colorpicker', 'iconpicker');
	
	private $_buttons          = array();
	private $_confirm_deletion = array();
	private $_errors           = array();
	private $_rules            = array();
	private $_display_required = TRUE;
	private $_fast_mode        = FALSE;
	
	public function add_rules($rules, $values = array())
	{
		if (!is_array($rules))
		{
			$rules = $this->load->form($rules, $values);
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

	public function add_back($url)
	{
		array_unshift($this->_buttons, array(
			'label'  => 'Retour',
			'action' => $this->config->base_url.($this->session->get_back() ?: $url)
		));

		return $this;
	}

	public function add_submit($label)
	{
		$this->_buttons[] = array(
			'type'  => 'submit',
			'label' => $label,
		);

		return $this;
	}

	public function confirm_deletion($title, $message = '')
	{
		$this->_confirm_deletion = array($title, $message);
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
		$post = post($this->id);
		
		if (strtolower($_SERVER['REQUEST_METHOD']) != 'post' || empty($post))
		{
			return FALSE;
		}
		
		if ($this->_confirm_deletion)
		{
			return $post === array('delete');
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

		foreach ($post as $key => &$value)
		{
			if (!in_array($key, array_keys($this->_rules)))
			{
				return FALSE;
			}
			else if (is_array($value))
			{
				array_walk_recursive($value, function(&$v, $k){
					$v = utf8_htmlentities($v);
				});
			}
			else if (!is_null($value))
			{
				$value = utf8_htmlentities($value);
			}
		}
		
		if (empty($this->_errors))
		{
			if ($this->_has_upload())
			{
				$files = $_FILES[$this->id];
				
				$this->load->library('file');
				
				foreach ($this->_rules as $var => $options)
				{
					if (isset($options['type']) && $options['type'] == 'file')
					{
						if (!empty($post[$var]) && $post[$var] == 'delete' && !empty($options['value']))
						{
							$this->file->delete($options['value']);
							$options['value'] = $post[$var] = NULL;
						}
						
						if (!empty($files['tmp_name'][$var]))
						{
							if (!($post[$var] = $this->file->upload($files, isset($options['upload']) ? $options['upload'] : NULL, $filename, isset($options['value']) ? $options['value'] : NULL, $var)))
							{
								$this->_errors[$var] = 'Erreur de transfert';
								return FALSE;
							}
							else if (isset($options['post_upload']) && is_callable($options['post_upload']))
							{
								call_user_func_array($options['post_upload'], array($filename));
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

	private function _check_text($post, $var, $options)
	{
		if (!in_array($post[$var], array('', NULL)) &&
			!empty($options['values']) &&
			is_array($options['values']) &&
			is_array($post[$var]) &&
			array_diff(array_filter($post[$var]), array_keys($options['values']))
		)
		{
			return 'La ou les valeurs entrées ne sont pas valides';
		}
		
		$is_file = !empty($options['type']) && $options['type'] == 'file';
		
		if (	!empty($options['rules']) &&
				in_array('required', $options['rules']) &&
				!in_array('disabled', $options['rules']) &&
				(
					($is_file && empty($_FILES[$this->id]['tmp_name'][$var])) ||
					(!$is_file && in_array($post[$var], array('', NULL)))
				)
			)
		{
			return 'Veuillez remplir ce champ';
		}
		
		if ($is_file && !empty($_FILES[$this->id]['error'][$var]) && $_FILES[$this->id]['error'][$var] != 4)
		{
			$errors = array(
				'La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini',
				'La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML',
				'Le fichier n\'a été que partiellement téléchargé',
				'Aucun fichier n\'a été téléchargé',
				'Un dossier temporaire est manquant',
				'Échec de l\'écriture du fichier sur le disque',
				'Une extension PHP a arrêté l\'envoi de fichier'
			);
			
			return $errors[$_FILES[$this->id]['error'][$var]  - 1];
		}
		
		if (isset($options['check']) && is_callable($options['check']))
		{
			if (!empty($options['type']) && $options['type'] == 'file')
			{
				$error = !empty($_FILES[$this->id]['tmp_name'][$var]) ? call_user_func_array($options['check'], array($_FILES[$this->id]['tmp_name'][$var], extension($_FILES[$this->id]['name'][$var]))) : TRUE;
			}
			else
			{
				$error = call_user_func_array($options['check'], array($post[$var], $post));
			}
			
			if (!in_array($error, array(TRUE, NULL), TRUE))
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
		$post[$var] = array_filter($post[$var], function($a){
			return strlen($a);
		});
		return $this->_check_text($post, $var, $options);
	}
	
	private function _check_email($post, $var, $options)
	{
		if (strlen($post[$var]) && !is_valid_email($post[$var]))
		{
			return 'Veuillez entrer une adresse email valide';
		}
		
		return $this->_check_text($post, $var, $options);
	}
	
	private function _check_url($post, $var, $options)
	{
		if (strlen($post[$var]) && !is_valid_url($post[$var]))
		{
			return 'Veuillez entrer une adresse url valide';
		}
		
		return $this->_check_text($post, $var, $options);
	}
	
	private function _check_date(&$post, $var, $options)
	{
		date2sql($post[$var]);
		return $this->_check_text($post, $var, $options);
	}
	
	public function display()
	{
		if ($this->_confirm_deletion)
		{
			list($title, $message) = $this->_confirm_deletion;

			if ($this->config->ajax_url)
			{
				return '<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
							<h4 class="modal-title">'.$title.'</h4>
						</div>
						<div class="modal-body">
							'.$message.'
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
							<a class="btn btn-danger delete-confirm" href="{base_url}'.$this->config->request_url.'" data-form-id="'.$this->id.'" onclick="return confirm_deletion(this);">Supprimer</a>
						</div>';
			}
			else
			{
				//TODO
				/*return 	'<p>'.$message.'</p><p>
							<button type="button" class="btn btn-default" onclick="$(this).parents(\'.alert\').alert(\'close\');">Annuler</button>
							<a class="btn btn-danger delete-confirm" href="{base_url}'.$this->config->request_url.'" data-form-id="'.$this->id.'" onclick="return confirm_deletion(this);">Supprimer</a>
						</p>';*/
			}

			return;
		}

		$output = '';
		
		if ($has_upload = $this->_has_upload())
		{
			$this->js('neofrag.file');
		}
		
		$output .= '<form'.(!$this->_fast_mode ? ' class="form-horizontal"' : '').' action="'.$this->config->base_url.$this->config->request_url.'" method="post"'.($has_upload ? ' enctype="multipart/form-data"' : '').'>
						<fieldset>';

		$post = post($this->id);
						
		foreach ($this->_rules as $var => $options)
		{
			if (!is_array($options) || !isset($options['type']) || !in_array($type = $options['type'], self::$types))
			{
				$type = 'text';
			}

			$output .= $this->{'_display_'.$type}($var, $options, isset($post[$var]) ? $post[$var] : NULL);
		}

		if ($this->_display_required)
		{
			$output .= '<div class="form-group"><div class="col-md-offset-3 col-md-9"><em class="text-muted">* Toutes les informations marquées d\'une étoile sont requises.</em></div></div>';
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
			return '<a href="'.$button['action'].'" class="btn btn-default">'.$button['label'].'</a>';
		}
		
		return '';
	}

	private function _display_label($var, $options)
	{
		$output = '';
		
		if (!empty($options['label']))
		{
			$output .= '<label class="control-label col-md-3" for="form_'.$this->id.'_'.$var.'">'.$options['label'];

			if ($options['label'] && isset($options['rules']) && in_array('required', $options['rules']) && $this->_display_required)
			{
				$output .= '<em>*</em>';
			}

			$output .= '</label>';
		}
		
		return $output;
	}

	private function _display_value($var, $options)
	{
		$post = post();

		if (isset($post[$this->id][$var]))
		{
			return $post[$this->id][$var];
		}
		else if (isset($options['value']))
		{
			return $options['value'];
		}
		
		return !empty($options['default']) ? $options['default'] : '';
	}

	private function _display_text($var, $options, $post, $type = 'text')
	{
		$typeahead = (isset($options['values']) && is_array($options['values']) && $options['values']) ? ' data-provide="typeahead" data-source="'.utf8_htmlentities('['.trim_word(implode(', ', array_map(create_function('$a', 'return \'"\'.$a.\'"\';'), $options['values'])), ', ').']').'"' : '';
		
		$output = '<div class="form-group'.((isset($this->_errors[$var])) ? ' has-error' : '').'">';
		
		if (!$this->_fast_mode)
		{
			$output .= $this->_display_label($var, $options).'<div class="col-md-9">';
		}
		
		$classes = array();
		
		if ($type == 'date')
		{
			NeoFrag::loader()	->css('bootstrap-datepicker/datepicker3')
								->js('bootstrap-datepicker/bootstrap-datepicker')
								->js('bootstrap-datepicker/locales/bootstrap-datepicker.fr')
								->js_load('$(".input-group.date").datepicker({format:"dd/mm/yyyy", language: "fr"});');
				
			$type = 'text';
			
			$classes[] = 'date';
			
			if (empty($options['icon']))
			{
				$options['icon'] = 'fa-calendar';
			}
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
		else if ($type == 'colorpicker')
		{
			$type = 'text';
			
			$classes[] = 'color';
			
			$options['icon'] = FALSE;
			
			NeoFrag::loader()	->css('bootstrap-colorpicker.min')
								->js('bootstrap-colorpicker.min')
								->js_load('$(".input-group.color").colorpicker({format: "hex", component: ".input-group-addon,input", colorSelectors: {default: "#777777", primary: "#337ab7", success: "#5cb85c", info: "#5bc0de", warning: "#f0ad4e", danger: "#d9534f"}});');
		}
		
		if (isset($options['icon']))
		{
			$output .= '<div class="input-group'.(!empty($classes) ? ' '.implode(' ', $classes) : '').'">
				<span class="input-group-addon">'.($options['icon'] ? $this->assets->icon($options['icon']) : '<i></i>').'</span>';
		}
		
		if ($type != 'file')
		{
			$class = ' class="form-control"';
			$value = ' value="'.$this->_display_value($var, $options).'"';
		}
		
		$input = '<input id="form_'.$this->id.'_'.$var.'" name="'.$this->id.'['.$var.']" type="'.$type.'"'.(!empty($value) ? $value : '').$typeahead.$this->_display_popover($var, $options).(!empty($class) ? $class : '').(($type == 'password' || $typeahead) && isset($options['autocomplete']) && $options['autocomplete'] === FALSE ? ' autocomplete="off"' : '').(!empty($options['rules']) && in_array('disabled', $options['rules']) ? ' disabled="disabled"' : '').($this->_fast_mode && !empty($options['label']) ? ' placeholder="'.$options['label'].'"' : '').' />';

		if ($type == 'file')
		{
			$post = post();
			
			$input = '<div style="margin: 7px 0;"><p>{fa-icon download} Télécharger un fichier'.(!empty($options['info']) ? $options['info'] : '').'</p>'.$input.'</div>';
			
			if (!empty($options['value']))
			{
				if (isset($post[$this->id][$var]) && $post[$this->id][$var] == 'delete')
				{
					$input = '<input type="hidden" name="'.$this->id.'['.$var.']" value="delete" />'.$input;
				}
				else
				{
					$input = '	<div class="row">
									<div class="col-md-3">
										<div class="thumbnail no-margin">
											<img src="'.$this->config->base_url.$this->db->select('path')->from('nf_files')->where('file_id', $options['value'])->row().'" alt="" />
											<div class="caption text-center">
												<a class="btn btn-outline btn-danger btn-xs form-file-delete" href="#" data-input="'.$this->id.'['.$var.']"><i class="fa fa-trash-o"></i> Supprimer</a>
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
		
		if (!$this->_fast_mode)
		{
			$output .= '</div>';
		}
		
		return $output.'</div>';
	}

	private function _display_iconpicker($var, $options, $post)
	{
		NeoFrag::loader()	->css('bootstrap-iconpicker.min')
							->js('iconset-fontawesome-4.2.0.min')
							->js('bootstrap-iconpicker.min');
		
		return '<div class="form-group'.((isset($this->_errors[$var])) ? ' has-error' : '').'">'.$this->_display_label($var, $options).'
					<div class="col-md-9">
						<button id="form_'.$this->id.'_'.$var.'" name="'.$this->id.'['.$var.']" class="btn btn-default" data-iconset="fontawesome" data-icon="'.$this->_display_value($var, $options).'" role="iconpicker"></button>
					</div>
				</div>';
	}

	private function _display_colorpicker($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'colorpicker');
	}
	
	private function _display_popover($var, $options)
	{
		$popover = '';
		$popover .= (isset($options['description'])) ? '<span class="text-info">'.$this->assets->icon('fa-info').'</span> '.$options['description'] : '';
		$popover .= (isset($options['description']) && isset($this->_errors[$var])) ? '<br /><br />' : '';
		$popover .= (isset($this->_errors[$var]) && $this->_errors[$var]) ? '<span class="text-danger">'.$this->assets->icon('fa-exclamation-triangle').' '.$this->_errors[$var].'</span>' : '';

		return $popover ? ' data-toggle="popover" data-placement="auto" data-html="true" data-content="'.utf8_htmlentities($popover).'" data-template="'.utf8_htmlentities('<div class="popover"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p></p></div></div></div>').'"' : '';
	}

	private function _display_password($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'password');
	}

	private function _display_date($var, $options, $post)
	{
		return $this->_display_text($var, $options, $post, 'date');
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
		$output = '<div class="form-group">
						<label class="control-label col-md-3">'.(isset($options['label']) ? $options['label'] : '').'</label>
						<div class="col-md-9">
							<div class="checkbox">
								<input type="hidden" name="'.$this->id.'['.$var.'][]" value="" />';
								
		foreach ($options['values'] as $value => $label)
		{
			 $output .= '	<label class="checkbox">
								<input type="checkbox" name="'.$this->id.'['.$var.'][]" value="'.$value.'"'.((is_null($post) && isset($options['checked'], $options['checked'][$value]) && $options['checked'][$value]) || ($post && in_array($value, $post)) ? ' checked="checked"' : '').' />
								'.$label.'
							</label>';
		}

		return $output.'</div></div></div>';
	}

	private function _display_radio($var, $options, $post)
	{
		$output = '<div class="form-group">
						<label class="control-label col-md-3">'.(isset($options['label']) ? $options['label'] : '').'</label>
						<div class="col-md-9">
							<input type="hidden" name="'.$this->id.'['.$var.']" value="" />';

		if (isset($options['values']) && $options['values'])
		{
			foreach ($options['values'] as $value => $label)
			{
				 $output .= '<label class="radio-inline">
									<input type="radio" name="'.$this->id.'['.$var.']" value="'.$value.'"'.((is_null($post) && isset($options['value']) && $options['value'] == $value) || $post == $value ? ' checked="checked"' : '').' />
									'.$label.'
								</label>';
			}
		}

		return $output.'</div></div>';
	}

	private function _display_select($var, $options, $post)
	{
		if (empty($options['values']) && (!isset($options['rules']) || !in_array('required', $options['rules'])))
		{
			return '';
		}
		
		$output = '<div class="form-group'.((isset($this->_errors[$var])) ? ' has-error' : '').'">'.$this->_display_label($var, $options).'
						<div class="col-md-9">
							<select class="form-control" id="form_'.$this->id.'_'.$var.'" name="'.$this->id.'['.$var.']"'.$this->_display_popover($var, $options).'>
								<option></option>';

		foreach ($options['values'] as $value => $label)
		{
			$output .= '<option value="'.$value.'"'.((is_null($post) && isset($options['value']) && $options['value'] == $value) || $post == $value ? ' selected="selected"' : '').'>'.$label.'</option>';
		}

		return $output.'</select></div></div>';
	}

	private function _display_textarea($var, $options, $post, $editor = FALSE)
	{
		$output = '<div class="form-group'.((isset($this->_errors[$var])) ? ' has-error' : '').'">';

		if (isset($options['label']))
		{
			$output .= '<label class="control-label col-md-3" for="form_'.$this->id.'_'.$var.'">'.$options['label'].(($options['label'] && isset($options['rules']) && in_array('required', $options['rules']) && $this->_display_required) ? '<em>*</em>' : '').'</label>';
		}
		
		$output .= '<div class="col-md-9">
						<textarea id="form_'.$this->id.'_'.$var.'" class="form-control'.($editor ? ' editor' : '').'" rows="10" name="'.$this->id.'['.$var.']">'.$this->_display_value($var, $options).'</textarea>';

		if (isset($this->_errors[$var]))
		{
			$output .= '<span class="help-inline">'.$this->_errors[$var].'</span>';
		}

		if (isset($options['description']))
		{
			$output .= '<p class="help-block">'.$options['description'].'</p>';
		}

		$output .= '	</div>
					</div>';

		return $output;

	}

	private function _display_editor($var, $options, $post)
	{
		$this	->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});');

		return $this->_display_textarea($var, $options, $post, TRUE);
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

/*
NeoFrag Alpha 0.1
./neofrag/libraries/form.php
*/