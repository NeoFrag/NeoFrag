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

	public function __sleep()
	{
		return array_merge(parent::__sleep(), ['_size']);
	}

	public function size($size)
	{
		$this->_size = $size;
		return $this;
	}

	public function __toString()
	{
		$size = '';

		foreach ($this as $i => $child)
		{
			if (method_exists($child, 'size') && !$size)
			{
				$size = $child->size();
			}
		}

		if ($this->_id !== NULL)
		{
			foreach ($this as $i => $child)
			{
				$child->id($i);
			}
		}

		$output = parent::__toString();

		if ($this->_id !== NULL && NeoFrag()->output->live_editor() & \NF\NeoFrag\Core\Output::COLS)
		{
			$output = '<div class="live-editor-col">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="-1" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Réduire').'">'.icon('fa-compress fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="1" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Augmenter').'">'.icon('fa-expand fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Supprimer').'">'.icon('fa-close').'</button>
							</div>
							<h3>'.NeoFrag()->lang('Col').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-widget" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Nouveau Widget').'">'.icon('fa-plus').'</button></div></h3>
							'.$output.'
						</div>';
		}

		return '<div class="'.($this->_size ?: $size ?: 'col-12').'"'.($this->_id !== NULL ? ' data-col-id="'.$this->_id.'"' : '').'>'.$output.'</div>';
	}
}
