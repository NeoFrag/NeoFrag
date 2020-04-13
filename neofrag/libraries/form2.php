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
	protected $_errors  = [];
	protected $_success = [];
	protected $_read_only;
	protected $_display;
	protected $_template;
	protected $_token;

	public function __invoke($form = '', $values = [])
	{
		$this->__id();

		if ($form)
		{
			$this->form($form, $values);
		}

		return $this;
	}

	public function __toString()
	{
		return (string)$this->_exec()->append(implode($this->_buttons()));
	}

	public function check()
	{
		$post = post();

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
		}

		unset($value);

		foreach ($this->_rules as $rule)
		{
			if (method_exists($rule, 'bind') && ($callback = $rule->bind()))
			{
				$callback(array_key_exists($rule->name(), $post) ? $post[$rule->name()] : $this->_values->{$rule->name()}, $rule);
			}
		}

		if (!$this->_read_only && isset($post['_']) && $post['_'] == $this->token())
		{
			$success = TRUE;
			$data    = [];

			foreach ($this->_rules as $rule)
			{
				if (method_exists($rule, 'check'))
				{
					$success = $rule->check($post, $data) && $success;
				}
			}

			if ($success)
			{
				if (is_a($this->_values, 'NF\NeoFrag\Loadables\Model2'))
				{
					foreach ($data as $key => $value)
					{
						$this->_values->set($key, $value);
					}

					$data = $this->_values;
				}

				foreach ($this->_success as $callback)
				{
					call_user_func_array($callback, [$data, $this]);
				}
			}

			return TRUE;
		}

		return FALSE;
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

	public function after($after, $rule)
	{
		$i = 0;
		while (current($this->_rules) != $after && next($this->_rules) && ++$i);
		array_splice($this->_rules, $i + 1, 0, [$rule]);
	}

	public function form($form, $values = [])
	{
		$this->_values = $values;

		if (func_num_args() == 1 && ($model = $this->model2($form)))
		{
			$this->_values = $model;
		}

		$found = FALSE;

		foreach (explode(' ', $form) as $form)
		{
			if ($path = $this->__caller->__path('forms', $form.'.php', $paths))
			{
				$found = TRUE;

				include $path;
			}
			else
			{
				trigger_error('Unfound form: '.$form.' in paths ['.implode(';', $paths).']', E_USER_WARNING);
			}
		}

		if ($found && is_a($this->_values, 'NF\NeoFrag\Loadables\Model2'))
		{
			foreach ($this->_rules as $rule)
			{
				if (method_exists($rule, 'value'))
				{
					if (is_a($value = $this->_values->{$rule->name()}, 'NF\NeoFrag\Loadables\Model2') && !is_a($value, 'NF\NeoFrag\Models\I18n'))
					{
						$value = $value->id;
					}

					$rule->value($value);
				}
			}
		}

		return $this;
	}

	public function model($rule = NULL)
	{
		if (is_a($this->_values, 'NF\NeoFrag\Loadables\Model2'))
		{
			return $rule ? $this->_values->{$rule->name()} : $this->_values;
		}
	}

	public function legend($legend, $icon = '')
	{
		$this->_rules[] = is_a($legend, 'NF\NeoFrag\Libraries\Forms\Legend') ? $legend : $this->form_legend($legend, $icon);
		return $this;
	}

	public function info($info)
	{
		$this->_rules[] = is_a($info, 'NF\NeoFrag\Libraries\Forms\Content') ? $info : $this->form_info($info);
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

	public function button_prepend($button)
	{
		array_unshift($this->_buttons, $button);
		return $this;
	}

	public function button($button)
	{
		$this->_buttons[] = $button;
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
		array_unshift($this->_success, $success);
		return $this;
	}

	public function error($error)
	{
		$this->_errors[] = $error;
		return $this;
	}

	public function read_only()
	{
		$this->_read_only = TRUE;
		return $this;
	}

	public function panel()
	{
		$this->_template = function($fields){
			return $this->array()
						->append($this	->html()
										->attr('class', 'card-body')
										->content($fields)
						)
						->append_if($buttons = $this->_buttons(), $this	->html()
																		->attr('class', 'card-footer text-right')
																		->content($buttons)
						);
		};

		return parent	::panel()
						->heading()
						->body($this->_exec(), FALSE);
	}

	public function modal($title, $icon = '')
	{
		$form = $this->_exec();

		$modal = parent	::modal($title, $icon)
						->body($form->content())
						->template(function(&$content) use ($form){
							$content = $form->content($content);
						});

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

		if (!$this->_read_only && !$this->_has_submit())
		{
			$buttons[] = $this->button_submit();
		}

		usort($buttons, function($a, $b){
			return strcmp(get_class($a), get_class($b));
		});

		return $buttons;
	}

	protected function _has_submit()
	{
		foreach ($this->_buttons as $button)
		{
			if (is_a($button, 'NF\NeoFrag\Libraries\Buttons\Submit'))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	protected function _exec()
	{
		$check = $this->check();

		$has_upload = FALSE;

		$rules = $this->_rules;

		array_unshift($rules, $this->form_hidden('_', $this->token()));

		foreach ($rules as $rule)
		{
			if (!$has_upload && is_a($rule, 'NF\NeoFrag\Libraries\Forms\File'))
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

		$errors = [];

		foreach ($this->_errors as $error)
		{
			$errors[] = $this	->html('p')
								->attr('class', 'text-danger')
								->content($this->label($error, 'fas fa-exclamation-triangle'));
		}

		$fields = [];

		foreach ($rules as $rule)
		{
			if ($this->_read_only && (method_exists($rule, $method = 'disabled') || method_exists($rule, $method = 'read_only')))
			{
				$rule->$method();
			}

			if (method_exists($rule, 'size') && $rule->size())
			{
				$last = end($fields);

				if (!is_a($last, 'NF\NeoFrag\Libraries\Html'))
				{
					$fields[] = $last = $this->html()->attr('class', 'form-row');
				}

				$last->append($rule);
			}
			else
			{
				$fields[] = $rule;
			}
		}

		$this->js('form');

		if ($this->url->ajax() && $check)
		{
			$this->output->json([
				'form' => implode(array_merge($errors, $fields))
			]);
		}

		if (!$this->_template)
		{
			$this->_template = function($fields){
				return implode($fields);
			};
		}

		return $this->html('form')
					->attr_if($this->_display & self::FORM_INLINE, 'class', 'form-inline')
					->attr('action', url($this->url->request))
					->attr('method', 'post')
					->attr_if($has_upload, 'enctype', 'multipart/form-data')
					->content(call_user_func($this->_template, array_merge($errors, $fields)));
	}
}
