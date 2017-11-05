<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Col extends Childrenable
{
	protected $_size;

	public function size($size)
	{
		$this->_size = $size;
		return $this;
	}

	public function __toString()
	{
		$size = '';

		foreach ($this->_children as $i => $child)
		{
			if (method_exists($child, 'size') && !$size)
			{
				$size = $child->size();
			}
		}

		if ($this->_id !== NULL)
		{
			foreach ($this->_children as $i => $child)
			{
				$child->id($i);
			}
		}

		$output = implode($this->_children);

		if ($this->_id !== NULL && NeoFrag::live_editor() & NeoFrag::COLS)
		{
			$output = '<div class="live-editor-col">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="-1" data-toggle="tooltip" data-container="body" title="'.$this->lang('reduce').'">'.icon('fa-compress fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="1" data-toggle="tooltip" data-container="body" title="'.$this->lang('increase').'">'.icon('fa-expand fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.$this->lang('remove').'">'.icon('fa-close').'</button>
							</div>
							<h3>'.$this->lang('col').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-widget" data-toggle="tooltip" data-container="body" title="'.$this->lang('new_widget').'">'.icon('fa-plus').'</button></div></h3>
							'.$output.'
						</div>';
		}

		return '<div class="'.($this->_size ?: $size ?: 'col-md-12').'"'.($this->_id !== NULL ? ' data-col-id="'.$this->_id.'"' : '').'>'.$output.'</div>';
	}
}
