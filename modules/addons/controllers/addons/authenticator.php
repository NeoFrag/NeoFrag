<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers\Addons;

use NF\NeoFrag\Loadables\Controller;

class Authenticator extends Controller
{
	public $__label = ['Authentificateurs', 'Authentificateur', 'fa-lock', 'info'];

	public function __actions()
	{
		return $this->array
					->set('enable', ['Activer', 'fa-check', 'success', TRUE, function($addon){
						return !$addon->is_enabled();
					}])
					->set('disable', ['Désactiver', 'fa-times', 'muted', TRUE, function($addon){
						return $addon->is_enabled();
					}])
					//->set('order', ['Ordre', 'fa-sort', 'info', TRUE])
					->set('settings', ['Configuration', 'fa-wrench', 'warning', TRUE]);
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
		return $this->modal('Authentificateurs', 'fa-sort')
					->body(
						$this	->table2(NeoFrag()->collection('addon')->where('type_id', NeoFrag()->collection('addon_type')->where('name', 'authenticator')->row()->id))
								->col(function($addon){
									return $this->button_sort($addon->name, 'admin/ajax/addons/language/sort', '.list-group', 'li');
								})
								->col(function($addon){
									return $addon->name;
								})
					)
					->submit('Ordonner', 'info')
					->cancel();
	}

	public function settings($auth)
	{
		return $this->form2()
					->info('<div class="alert alert-primary">
								<h5 class="alert-heading">'.$this->label('Informations', 'fa-info-circle').'</h5>
								<dl>
									<dt>Enregistrez votre site via</dt>
										<dd><a href="'.$auth->info()->help.'" target="_blank">'.$auth->info()->help.'</a></dd>
									'.$this	->array($auth->_params())
											->each(function($a, $key){
												return '<dt>'.$key.'</dt><dd>'.$a.'</dd>';
											}).'
								</dl>
							</div>')
					->exec(function($form) use ($auth){
						foreach (['dev' => 'Développement', 'prod' => 'Production'] as $type => $legend)
						{
							$form	->legend($legend)
									->exec(function($form) use ($type, $auth){
										foreach ($auth->_keys as $name)
										{
											$form->rule($this	->form_text($type.'_'.$name)
																->title($name)
																->value($auth->settings()->$type->$name));
										}
									});
						}
					})
					->success(function($data) use ($auth){
						foreach (['dev', 'prod'] as $type)
						{
							foreach ($auth->_keys as $key)
							{
								$auth->__addon->data->set($type, $key, $data[$type.'_'.$key]);
							}
						}

						$auth->__addon->set('data', $auth->__addon->data)->update();

						notify($this->lang('Configuration de <b>%s</b> modifiée', $auth->info()->title));

						refresh();
					})
					->modal($auth->info()->title, 'fa-wrench')
					->cancel();
	}
}
