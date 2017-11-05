<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_partners_c_index extends Controller_Module
{
	public function index()
	{
		$partners = $this->model()->get_partners();

		if (!empty($partners))
		{
			return $this->panel()
						->heading('Nos partenaires', 'fa-star-o')
						->body($this->view('index', [
							'partners' => $partners
						]));
		}
		else
		{
			return $this->panel()
						->heading('Nos partenaires', 'fa-star-o')
						->body('<div class="text-center">Aucun partenaire</div>')
						->color('info');
		}
	}
}
