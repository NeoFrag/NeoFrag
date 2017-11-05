<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_submit extends Button
{
	public function __invoke($title = '')
	{
		parent::__invoke();

		$this->_template[] = function(&$content, &$attrs, &$tag){
			$attrs['type'] = 'submit';
			$tag = 'button';
		};

		return $this->title($title ?: $this->lang('save'))
					->color('primary');
	}
}
