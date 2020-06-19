<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Bbcode extends Textarea
{
	protected $_html;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if ($this->_html)
			{
				$check = $this->_html[0]->required_if($this->_required)->check($post);

				if (isset($post[$this->_name.'_type']) && $post[$this->_name.'_type'] == 'html')
				{
					$this->_html[1] = FALSE;

					if (!$check)
					{
						$this->_errors = $this->_html[0]->errors();
					}
					else
					{
						$this->_errors      = [];
						$data[$this->_name] = $post[$this->_name.'_html'];
					}
				}
			}
		};

		$this->_template[] = function(&$input){
			$this	->css('wbbtheme')
					->js('jquery.wysibb.min')
					->js('jquery.wysibb.fr')
					->js('form')
					->js('form_bbcode');

			$input->append_attr('class', 'editor');

			if ($this->_html)
			{
				$this->js('bbcode');

				$input = parent	::html()
								->attr('class', 'editor-bbcode-html')
								->content([
									parent	::form_hidden($this->_name.'_type', $this->_html[1] ? 'bbcode' : 'html'),
									parent	::html()
											->attr('class', !$this->_html[1] ? 'hidden' : '')
											->content($input),
									parent	::html()
											->attr('class', 'textarea')
											->append_attr_if($this->_html[1], 'class', 'hidden')
											->content($this->_html[0]	->disabled_if($this->_disabled)
																		->read_only_if($this->_read_only)
																		->placeholder($this->_placeholder)
																		->rows($this->_rows)),
									parent	::html()
											->attr('class', 'editor-buttons')
											->content([
												'<br />',
												parent	::button()
														->title('BBCode')
														->icon('fas fa-bold')
														->color($this->_html[1] ? 'primary' : 'secondary')
														->data('type', 'bbcode'),
												'&nbsp;',
												parent	::button()
														->title('Code HTML')
														->icon('fas fa-code')
														->color(!$this->_html[1] ? 'primary' : 'secondary')
														->data('type', 'html')
											])
								]);
			}
		};

		return $this;
	}

	public function html($value = NULL)
	{
		$this->_html = [parent::form_textarea($this->_name.'_html')->value_if($value !== NULL, $value), $value === NULL];
		return $this;
	}
}
