<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class I18n
{
	protected $_multiline = FALSE;

	public function __construct($type = '')
	{
		if ($type == 'multiline')
		{
			$this->_multiline = TRUE;
		}
	}

	public function value($value, $model, $field)
	{
		$lang = NeoFrag()->model2('addon')->get('language', NeoFrag()->config->lang, FALSE);

		return NeoFrag()->collection('i18n')
						->where('lang_id',  $lang->id)
						->where('model',    $model->__table)
						->where('model_id', $model->id)
						->where('name',     $field->name)
						->row();
	}
}
