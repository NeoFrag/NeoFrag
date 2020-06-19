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
		$size = $this->_size;

		foreach ($this as $i => $child)
		{
			if (method_exists($child, 'size') && !$size)
			{
				$size = $child->size();
			}
		}

		if (preg_match('/^col-(\d+)$/', $size, $match) && $match[0] < 12)
		{
			$size = 'col-12 col-lg-'.$match[1];
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
								<button type="button" class="btn btn-sm btn-light live-editor-size" data-size="-1" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Réduire').'">'.icon('fas fa-compress fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-light live-editor-size" data-size="1" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Augmenter').'">'.icon('fas fa-expand fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Supprimer').'">'.icon('fas fa-times').'</button>
							</div>
							<h3>'.NeoFrag()->lang('Col').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-widget" data-toggle="tooltip" data-container="body" title="'.NeoFrag()->lang('Nouveau Widget').'">'.icon('fas fa-plus').'</button></div></h3>
							'.$output.'
						</div>';
		}

		return '<div class="'.($size ?: 'col-12').'"'.($this->_id !== NULL ? ' data-col-id="'.$this->_id.'"' : '').'>'.$output.'</div>';
	}
}
