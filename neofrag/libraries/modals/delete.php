<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Modals;

use NF\NeoFrag\Libraries\Modal;

class Delete extends Modal
{
	public function __invoke($title = '', $icon = '')
	{
		return parent	::__invoke($title ?: 'Confirmation de suppression', $icon ?: 'fa-time')
						->submit('Supprimer', 'danger')
						->cancel();
	}
}
