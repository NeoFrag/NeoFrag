<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers\Addons;

use NF\NeoFrag\Loadables\Controller;

class Language extends Controller
{
	public $__label = ['Langues', 'Langue', 'far fa-flag', 'danger'];

	public function __actions()
	{
		return $this->array
					->set('enable', ['Activer', 'fas fa-check', 'success', TRUE, function($addon){
						return !$addon->is_enabled();
					}])
					->set('disable', ['Désactiver', 'fas fa-times', 'muted', TRUE, function($addon){
						return count($this->config->langs) > 1 && $addon->is_enabled();
					}])
					->set('order', ['Ordre', 'fas fa-sort', 'info', TRUE]);
	}

	public function enable($addon)
	{
		$addon->__addon->set('data', $addon->__addon->data->set('enabled', TRUE))->update();

		notify($this->lang('<b>%s</b> activé', $addon->info()->title));

		refresh();
	}

	public function disable($addon)
	{
		$addon->__addon->set('data', $addon->__addon->data->set('enabled', FALSE))->update();

		notify($this->lang('<b>%s</b> désactivé', $addon->info()->title));

		refresh();
	}

	public function order()
	{
		$langs = $this	->array($this->config->langs)
						->sort(function($a, $b){
							return strnatcmp($a->settings()->order, $b->settings()->order);
						});

		if (($post = post_check('id', 'position')) && (list($addon_id, $position) = array_values($post)))
		{
			foreach ($langs as $id => $lang)
			{
				if ($lang->__addon->id == $addon_id)
				{
					break;
				}
			}

			foreach ($langs->move($id, $position)->values() as $order => $addon)
			{
				$addon->__addon	->set('data', $addon->__addon->data->set('order', $order))
								->update();
			}

			return $this->output->json(['success' => 'refresh']);
		}

		return $this->modal('Préférence des langues', 'far fa-flag')
					->body($this->table2($langs)
								->compact(function($a){
									return $this->button_sort($a->__addon->id, 'admin/addons/order/'.$a->__addon->url());
								})
								->col(function($a){
									return $this->label($a->info()->title, $a->info()->icon);
								})
					)
					->close();
	}
}
