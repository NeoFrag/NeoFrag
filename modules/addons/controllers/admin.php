<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$addons = array_filter(NeoFrag()->collection('addon')->get(), function($addon){
			$object = $addon->addon();

			if ($object && ($controller = $addon->controller()))
			{
				$actions = $object->__actions = $controller->__actions()->filter(function($action) use ($object){
					return !isset($action[4]) || $action[4]($object);
				});

				return !$actions->empty();
			}
		});

		$types = array_count_values(array_map(function($a){
			return $a->type->id;
		}, $addons));

		usort($addons, function($a, $b) use ($types){
			if ($types[$a->type->id] > $types[$b->type->id])
			{
				return 1;
			}
			else if ($types[$a->type->id] < $types[$b->type->id])
			{
				return -1;
			}
			else
			{
				return str_nat($a, $b, function($a){
					return $a->type->name.$a->addon()->info()->title;
				});
			}
		});

		$this->add_action($this->button('Ajouter', 'fas fa-plus', 'primary')->modal_ajax('admin/ajax/addons/install'));

		return $this->module('settings')->controller('admin')->_layout(function($col) use ($addons){
			$col->append($this	->js('mixitup.min')
								->js('addons')
								->css('addons')
								->view('admin', [
									'addons' => $addons
								])
			);
		});
	}

	public function help($controller, $method)
	{
		return $this->modal('Aide', 'far fa-life-ring')
					->large()
					->body(call_user_func([$controller, $method]))
					->close();
	}

	public function _action($addon, $controller, $action)
	{
		return $controller->$action($addon, $this);
	}
}
