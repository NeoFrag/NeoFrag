<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function post($gallery_id)
	{
		return $this->form2('post')
					->compact()
					->success(function($data, $form) use ($gallery_id){
						$this->model()->add_image(	$data['image']->id,
													$gallery_id,
													$data['title'],
													$data['description']);

						notify('Image postée dans l\'album avec succès !');
						refresh();
					})
					->modal('Poster une image', 'far fa-image')
					->cancel();
	}

	public function image($image)
	{
		return $this->modal($image['title'], 'far fa-image')
					->large()
					->body($this->view('image', [
						'original_file_id' => $image['original_file_id']
					]), FALSE)
					->close();
	}
}
