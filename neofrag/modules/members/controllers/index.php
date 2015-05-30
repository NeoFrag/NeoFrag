<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_members_c_index extends Controller_Module
{
	public function index($members)
	{
		$this	->title('Liste des membres')
				->load->library('table')
				->add_columns(array(
					array(
						'content' => '<img class="img-avatar-members" style="max-height: 40px; max-width: 40px;" src="<?php echo $NeoFrag->user->avatar($data[\'avatar\'], $data[\'sex\']); ?>" title="<?php echo $data[\'username\']; ?>" alt="" />',
						'size'    => TRUE
					),
					array(
						'title'   => 'Membre',
						'content' => '<div><?php echo $NeoFrag->user->link($data[\'user_id\'], $data[\'username\']); ?></div><small><i class="fa fa-circle <?php echo $data[\'online\'] ? \'text-green\' : \'text-gray\'; ?>"></i> <?php echo $data[\'admin\'] ? \'Admin\' : \'Membre\'; ?> <?php echo $data[\'online\'] ? \'en ligne\' : \'hors ligne\'; ?></small>',
						'search'  => '{username}'
					)/*,
					array(
						//TODO link compose
						'content' => '<?php echo $this->user() ? \'<a href="{base_url}user/compose.html"><i class="fa fa-envelope-o"></i> Contacter</a>\' : \'\' ?>',
						'size'    => TRUE,
						'align'   => 'right'
					)*/
				))
				->data($members)
				->no_data('Il n\'y a pas encore de membre dans ce groupe');
			
		return new Panel(array(
			'title'   => 'Liste des membres',
			'icon'    => 'fa-users',
			'content' => $this->table->display()
		));
	}

	public function _member($user_id, $username)
	{
		$this->title($username);
		
		return array(
			new Panel(array(
				'title'   => $username,
				'icon'    => 'fa-user',
				'content' => $this->load->view('profile', $this->model()->get_member_profile($user_id)),
			)),
			new Button_back('members.html')
		);
	}
	
	public function _group($title, $members)
	{
		$output = array($this->index($members));
		
		array_unshift($output, new Panel(array(
			'content' => '<h2 class="no-margin">Groupe <small>'.$title.'</small>'.button('{base_url}members.html', 'fa-close', 'Voir tous les membres', 'danger', 'pull-right').'</h2>'
		)));

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/members/controllers/index.php
*/