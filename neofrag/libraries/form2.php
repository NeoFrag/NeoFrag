<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Form2 extends Library
{
	const FORM_INLINE  = 1;
	const FORM_COMPACT = 2;

	protected $_buttons = [];
	protected $_rules   = [];
	protected $_values  = [];
	protected $_deleting;
	protected $_success;
	protected $_display;
	protected $_template;
	protected $_token;

	public function __invoke($form = '', $values = [])
	{
		$this->__id();

		if ($form)
		{
			foreach ($paths = $this->load->paths('forms') as $dir)
			{
				if (!check_file($path = $dir.'/'.$form.'.php'))
				{
					continue;
				}

				$found = TRUE;

				if (\NEOFRAG_DEBUG_BAR || \NEOFRAG_LOGS)
				{
					$this->load->forms[$dir] = [$path, $form.'.php'];
				}

				if (is_array($rules = include $path))
				{
					$this->_rules = $rules;
				}

				$this->_values = $values;

				if (func_num_args() == 1 && ($model = $this->model2($form)))
				{
					$this->_values = $model;
				}

				if (is_a($this->_values, 'NF\\NeoFrag\\Loadables\\Model2'))
				{
					foreach ($this->_rules as $rule)
					{
						if (method_exists($rule, 'value'))
						{
							if (is_a($value = $this->_values->{$rule->name()}, 'NF\\NeoFrag\\Loadables\\Model2'))
							{
								$value = $value->id;
							}

							$rule->value($value);
						}
					}
				}

				break;
			}

			if (empty($found))
			{
				trigger_error('Unfound form: '.$form.' in paths ['.implode(';', $paths).']', E_USER_WARNING);
			}
		}

		return $this;
	}

	public function __toString()
	{
		$check = $this->_check();

		$has_upload = FALSE;

		$rules = $this->_rules;

		$rules[] = $this->form_hidden('_', $this->token());

		foreach ($rules as $rule)
		{
			if (!$has_upload && is_a($rule, 'NF\\NeoFrag\\Libraries\\Forms\\File'))
			{
				$has_upload = TRUE;
			}

			if (method_exists($rule, 'form2'))
			{
				$rule->form2($this);
			}
		}

		if ($has_upload)
		{
			$this->js('file');
		}

		if ($check && $this->url->ajax())
		{
			$this->output->json([
				'form' => implode($rules)
			]);
		}

		if (!$this->_template)
		{
			$this->_template = function($fields){
				return implode($fields).$this->_buttons();
			};
		}

		return (string)$this->html('form')
							->attr_if($this->_display & self::FORM_INLINE, 'class', 'form-inline')
							->attr('action', url($this->url->request))
							->attr('method', 'post')
							->attr_if($has_upload, 'enctype', 'multipart/form-data')
							->content(call_user_func($this->_template, $rules));
	}

	protected function _check()
	{
		$post = post();

		if ($this->_success && isset($post['_']) && $post['_'] == $this->token())
		{
			$success = TRUE;
			$data    = [];

			foreach ($post as $key => &$value)
			{
				if (is_array($value))
				{
					array_walk_recursive($value, function(&$v){
						$v = utf8_htmlentities(trim($v));
					});
				}
				else if ($value !== NULL)
				{
					$value = utf8_htmlentities(trim($value));
				}

				unset($value);
			}

			foreach ($this->_rules as $rule)
			{
				if (method_exists($rule, 'check'))
				{
					$success = $rule->check($post, $data) && $success;
				}
			}

			if ($success)
			{
				if (is_a($this->_values, 'NF\\NeoFrag\\Loadables\\Model2'))
				{
					foreach ($data as $key => $value)
					{
						$this->_values->set($key, $value);
					}

					$data = $this->_values;
				}

				call_user_func_array($this->_success, [$data, $this]);
			}

			return TRUE;
		}
	}

	public function token($id = NULL)
	{
		if ($id === NULL)
		{
			$id = $this->__id();
		}

		static $_tokens;

		if ($_tokens === NULL)
		{
			$_tokens = $this->session('form2') ?: [];
		}

		if (empty($_tokens[$id]))
		{
			$this->session->set('form2', $id, $_tokens[$id] = unique_id(array_merge([$id], $_tokens)));
		}

		return $_tokens[$id];
	}

	public function rule($rule, $title = '', $value = '', $type = 'text')
	{
		if (is_string($rule))
		{
			$rule = $this	->{'form_'.$type}($rule)
							->title($title)
							->value($value);
		}

		$this->_rules[] = $rule;

		return $this;
	}

	public function legend($legend, $icon = '')
	{
		$this->_rules[] = is_a($legend, 'NF\\NeoFrag\\Libraries\\Forms\\Legend') ? $legend : $this->form_legend($legend, $icon);
		return $this;
	}

	public function captcha()
	{
		$this->_rules[] = $this->form_captcha();
		return $this;
	}

	public function submit($title = '', $color = 'primary')
	{
		$this->_buttons[] = $this->button_submit($title, $color);
		return $this;
	}

	public function back($url = '')
	{
		$this->_buttons[] = $this->button_back($url);
		return $this;
	}

	public function compact()
	{
		$this->_display |= self::FORM_COMPACT;
		return $this;
	}

	public function inline()
	{
		$this->_display |= self::FORM_INLINE;
		return $this;
	}

	public function display()
	{
		return $this->_display;
	}

	public function success($success)
	{
		$this->_success = $success;
		return $this;
	}

	public function delete($deleting, $callback)
	{
		$this->_deleting = $deleting;
		$this->_success  = $callback;

		return $this->submit($this->lang('Supprimer'), 'danger');
	}

	public function panel()
	{
		$this->_template = function($fields){
			return 	$this	->html()
							->attr('class', 'panel-body')
							->content($fields).
					$this	->html()
							->attr('class', 'panel-footer')
							->content($this->_buttons());
		};

		return parent	::panel()
						->heading()
						->body($this, FALSE);
	}

	public function modal($title, $icon = '')
	{
		$this->_template = function($fields){
			return $this->html()
						->attr('class', 'modal-body')
						->content($fields)
						->append_content_if($this->_deleting, $this->_deleting);
		};

		$modal = parent	::modal($title, $icon)
						->body($this, FALSE);

		foreach ($this->_buttons as $button)
		{
			$modal->button($button);
		}

		if (!$this->_has_submit())
		{
			$modal->submit();
		}

		return $modal;
	}

	protected function _buttons()
	{
		$buttons = $this->_buttons;

		if (!$this->_has_submit())
		{
			$buttons[] = $this->button_submit();
		}

		usort($buttons, function($a, $b){
			return strcmp(get_class($a), get_class($b));
		});

		return $this->html()
					->attr('class', 'form-group')
					->content(
						$this	->html()
								->attr('class', 'col-md-offset-3 col-md-9')
								->content(implode('&nbsp;', $buttons))
					);
	}

	protected function _has_submit()
	{
		foreach ($this->_buttons as $button)
		{
			if (is_a($button, 'NF\\NeoFrag\\Libraries\\Buttons\\Submit'))
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}
