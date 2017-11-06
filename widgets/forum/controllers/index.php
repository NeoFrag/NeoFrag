<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($config = [])
	{
		$messages = $this->model()->get_last_messages();

		if (!empty($messages))
		{
			return $this->panel()
						->heading($this->lang('Derniers messages'))
						->body($this->view('index', [
							'messages' => $messages
						]))
						->footer('<a href="'.url('forum').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('Accèder au forum').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Derniers messages'))
						->body($this->lang('Aucun message pour le moment'));
		}
	}

	public function topics($config = [])
	{
		$topics = $this->model()->get_last_topics();

		if (!empty($topics))
		{
			return $this->panel()
						->heading($this->lang('Derniers sujets'))
						->body($this->view('topics', [
							'topics' => $topics
						]))
						->footer('<a href="'.url('forum').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('Accèder au forum').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Derniers sujets'))
						->body($this->lang('Aucun sujet pour le moment'));
		}
	}

	public function statistics($config = [])
	{
		return $this->panel()
					->heading($this->lang('Statistiques'), 'fa-signal')
					->body($this->view('statistics', [
						'topics'    => $topics = $this->db->select('COUNT(topic_id)')->from('nf_forum_topics')->row(),
						'messages'  => $this->db->select('COUNT(message_id)')->from('nf_forum_messages')->row() - $topics,
						'announces' => $this->db->select('COUNT(topic_id)')->from('nf_forum_topics')->where('status', ['-2', '1'])->row(),
						'users'     => $this->db->select('COUNT(DISTINCT user_id)')->from('nf_forum_messages')->row()
					]), FALSE);
	}

	public function activity($config = [])
	{
		$users = $this->db->select('DISTINCT u.user_id', 'u.username')->from('nf_sessions s')->join('nf_users u', 'u.user_id = s.user_id AND u.deleted = "0"', 'INNER')->where('s.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('s.is_crawler', FALSE)->get();

		array_natsort($users, function($a){
			return $a['username'];
		});

		return $this->panel()
					->heading($this->lang('Activité du forum'), 'fa-globe')
					->body($this->view('activity', [
						'users'    => $users,
						'visitors' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('user_id', NULL)->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row()
					]));
	}
}
