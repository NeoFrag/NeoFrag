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
	protected $_is_model;
	protected $_collection;
	protected $_read_only;
	protected $_display;
	protected $_template;
	protected $_token;
	protected $_check;

	public function __invoke(...$args)
	{
		$this->__id();

		if ($args)
		{
			$this->form(...$args);
		}

		return $this;
	}

	public function __toString()
	{
		return (string)$this->_exec()->append(implode($this->_buttons()));
	}

	public function check($load_filters = FALSE)
	{
		if ($this->_check === NULL)
		{
			$post = post();

			array_walk_recursive($post, function(&$value){
				$v = utf8_htmlentities(trim($value));
			});

			foreach ($this->_rules as $rule)
			{
				if (method_exists($rule, 'bind') && ($callback = $rule->bind()))
				{
					$callback(array_key_exists($rule->name(), $post) ? $post[$rule->name()] : $this->_values->{$rule->name()}, $rule);
				}
			}

			$filters = function($data = NULL, &$session = []) use ($load_filters){
				$session = [];

				if ($load_filters)
				{
					$filters = FALSE;

					foreach ($this->_rules as $rule)
					{
						if (method_exists($rule, 'name'))
						{
							$name = $rule->name();

							$value = '';

							if ($data === NULL)
							{
								$value = $rule->default();
							}
							else if (array_key_exists($name, $data))
							{
								$value = $data[$name];
							}

							if (method_exists($rule, 'value') && $data !== NULL)
							{
								$rule->value($value, TRUE);

								if (!is_empty($value))
								{
									$session[$name] = $value;
								}
							}

							if (method_exists($rule, 'filter') && ($filter = $rule->filter()))
							{
								$filters = TRUE;

								if (array_key_exists($name, $session))
								{
									if (isset($filter[1]) && is_a($filter[1], 'closure'))
									{
										call_user_func_array($filter[1], [$this->_collection]);
									}

									if (is_a($filter[0], 'closure'))
									{
										call_user_func_array($filter[0], [$this->_collection, $value]);
									}
									else
									{
										$this->_collection->where($filter[0], $value, 'AND');
									}
								}
							}
						}
					}

					if (!$filters)
					{
						$session = [];
					}
				}
			};

			$this->_check = !$this->_read_only && isset($post['_']) && $post['_'] == $this->token();

			if ($this->_check)
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
					$filters($data, $session);

					$this->session->set('table2', 'filters', $this->__id(), $session);

					if ($this->_success)
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
				}
			}
			else
			{
				$filters($this->session->get('table2', 'filters', $this->__id()));
			}
		}

		return $this->_check;
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

	public function collection($collection)
	{
		$this->_collection = $collection;
		return $this;
	}

	public function after($after, $rule)
	{
		$i = 0;
		while (current($this->_rules) != $after && next($this->_rules) && ++$i);
		array_splice($this->_rules, $i + 1, 0, [$rule]);
	}

	public function form(...$args)
	{
		$model = array_pop($args);
		$this->_is_model = is_a($model, 'NF\NeoFrag\Loadables\Model2');

		if ($this->_is_model || is_array($model))
		{
			$this->_values = $model;
			$form = array_pop($args);
		}
		else
		{
			$form = $model;
		}

		foreach (strtoarray(' ', $form) as $form)
		{
			if ($path = $this->__caller->__path('forms', $form.'.php', $paths))
			{
				include $path;
			}
			else
			{
				trigger_error('Unfound form: '.$form.' in paths ['.implode(';', $paths).']', E_USER_WARNING);
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
		if ($this->_values)
		{
			foreach ($this->_rules as $rule)
			{
				if (method_exists($rule, 'value'))
				{
					$name  = $rule->name();
					$value = NULL;

					if ($this->_is_model)
					{
						$value = $this->_values->$name;
					}
					else if (array_key_exists($name, $this->_values))
					{
						$value = $this->_values[$name];
					}

					if (is_a($value, 'NF\NeoFrag\Loadables\Model2') && !is_a($value, 'NF\NeoFrag\Models\I18n'))
					{
						$value = $value->id;
					}

					$rule->value($value);
				}
			}
		}

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
