<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_checkbox('charte')
					->data([
						'on' => 'En vous inscrivant, vous acceptez notre <a href="#collapseCharte" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCharte">charte d\'inscription</a>
								<div class="collapse" id="collapseCharte">
									<div class="card card-body mt-2">'.bbcode($this->config->nf_registration_charte).'</div>
								</div>'
					])
					->required()
		);
