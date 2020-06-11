<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_image('image', 'gallery')
					->title('Image')
					->required()
		)
		->rule($this->form_text('title')
					->title('Titre de l\'image')
		)
		->rule($this->form_textarea('description')
					->title('Description')
					->rows(5)
		)
		->submit($this->lang('Ajouter l\'image'));
