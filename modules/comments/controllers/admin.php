<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($comments)
	{
		return $this->table2($comments, 'Aucun commentaire')
					->col('Module', function($comment){
						return $comment->module;
					})
					->col('Auteur', function($comment){
						return $comment->user->link();
					})
					->col('Date', 'date')
					->col('Message', 'content')
					->panel();
	}
}
