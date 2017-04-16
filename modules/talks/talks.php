<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Talks;

use NF\NeoFrag\Addons\Module;

class Talks extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('talks'),
			'description' => '',
			'icon'        => 'fa-comment-o',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'routes'      => [
				'admin{pages}'            => 'index',
				'admin/{id}/{url_title*}' => '_edit'
			]
		];
	}

	public function permissions()
	{
		return [
			'talk' => [
				'get_all' => function(){
					return NeoFrag()->db->select('talk_id', 'CONCAT_WS(" ", "{lang talks}", name)')->from('nf_talks')->where('talk_id >', 1)->get();
				},
				'check'   => function($talk_id){
					if ($talk_id > 1 && ($talk = NeoFrag()->db->select('name')->from('nf_talks')->where('talk_id', $talk_id)->row()) !== [])
					{
						return '{lang talks} '.$talk;
					}
				},
				'init'    => [
					'read'   => [
					],
					'write'  => [
						['visitors', FALSE]
					],
					'delete' => [
						['admins', TRUE]
					]
				],
				'access'  => [
					[
						'title'  => $this->lang('talks'),
						'icon'   => 'fa-comment-o',
						'access' => [
							'read' => [
								'title' => $this->lang('read'),
								'icon'  => 'fa-eye'
							],
							'write' => [
								'title' => $this->lang('write'),
								'icon'  => 'fa-reply'
							]
						]
					],
					[
						'title'  => $this->lang('moderation'),
						'icon'   => 'fa-user',
						'access' => [
							'delete' => [
								'title' => $this->lang('delete_message'),
								'icon'  => 'fa-trash-o'
							]
						]
					]
				]
			]
		];
	}
}
