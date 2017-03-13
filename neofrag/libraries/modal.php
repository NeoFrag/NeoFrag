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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Modal extends Library
{
	protected $_header;
	protected $_buttons = [];
	protected $_body;
	protected $_size;
	protected $_form;

	public function __invoke($title, $icon = '')
	{
		$this->_header = is_a($title, 'Label') ? $title : $this->label($title, $icon);

		return NeoFrag()->modals[] = $this->reset();
	}

	public function __toString()
	{
		$content = '<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="'.$this->lang('close').'"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">'.$this->_header.'</h4>
					</div>
					<div class="modal-body">'.$this->_body.'</div>
					'.($this->_buttons ? $this->button->static_footer($this->_buttons, 'right')->append_attr('class', 'modal-footer') : '');

		if ($this->_form)
		{
			$content = $this->html('form')
							->attr('action', url($this->url->request))
							->attr('method', 'post')
							->content($content);
		}

		$content = $this->html()
						->attr('class', 'modal-content')
						->content($content);

		return '<div id="'.$this->id.'" class="modal fade" tabindex="-1" role="dialog">
					<div class="modal-dialog'.($this->_size ? ' modal-'.$this->_size : '').'" role="document">
						'.$content.'
					</div>
				</div>';
	}

	public function body($body)
	{
		$this->_body = $body;
		return $this;
	}

	public function button($button)
	{
		$this->_buttons[] = $button;
		return $this;
	}

	public function dismiss($title)
	{
		array_unshift($this->_buttons, parent	::button()
												->title($title)
												->color('default')
												->align('right')
												->data('dismiss', 'modal'));

		return $this;
	}

	public function primary($button = '', $color = 'primary')
	{
		if (!is_a($button, 'Button'))
		{
			$button = parent::button()
							->title($button ?: $this->lang('save'))
							->color($color);
		}

		$this->_buttons[] = $button->align('right');

		return $this;
	}

	public function submit($button = '', $color = 'primary')
	{
		$this->_form = TRUE;

		if (!is_a($button, 'Button'))
		{
			$button = parent::button_submit()
							->title($button ?: $this->lang('save'))
							->color($color);
		}

		$this->_buttons[] = $button->align('right');

		return $this;
	}

	public function close()
	{
		return $this->dismiss($this->lang('close'));
	}

	public function cancel()
	{
		return $this->dismiss($this->lang('cancel'));
	}

	public function large()
	{
		$this->_size = 'lg';
		return $this;
	}

	public function small()
	{
		$this->_size = 'sm';
		return $this;
	}

	public function open()
	{
		NeoFrag()->js_load('$(\'#'.$this->id.'\').modal(\'show\');');
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/modal.php
*/