<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Displayables;

use NF\NeoFrag\Displayable;

class Col extends Displayable
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

		$output = implode($this->_children->__toArray());

		if ($this->_id !== NULL && NEOFRAG_LIVE_EDITOR & NEOFRAG_COLS)
		{
			$output = '<div class="live-editor-col">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="-1" data-toggle="tooltip" data-container="body" title="'.$this->lang('Réduire').'">'.icon('fa-compress fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="1" data-toggle="tooltip" data-container="body" title="'.$this->lang('Augmenter').'">'.icon('fa-expand fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.$this->lang('Supprimer').'">'.icon('fa-close').'</button>
							</div>
							<h3>'.$this->lang('Col').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-widget" data-toggle="tooltip" data-container="body" title="'.$this->lang('Nouveau Widget').'">'.icon('fa-plus').'</button></div></h3>
							'.$output.'
						</div>';
		}

		return '<div class="'.($this->_size ?: $size ?: 'col-md-12').'"'.($this->_id !== NULL ? ' data-col-id="'.$this->_id.'"' : '').'>'.$output.'</div>';
	}
}
