<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_talks extends Module
{
	public $title       = '{lang talks}';
	public $description = '';
	public $icon        = 'fa-comment-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
		'admin{pages}'            => 'index',
		'admin/{id}/{url_title*}' => '_edit'
	];

	public static function permissions()
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
						'title'  => '{lang talks}',
						'icon'   => 'fa-comment-o',
						'access' => [
							'read' => [
								'title' => '{lang read}',
								'icon'  => 'fa-eye'
							],
							'write' => [
								'title' => '{lang write}',
								'icon'  => 'fa-reply'
							]
						]
					],
					[
						'title'  => '{lang moderation}',
						'icon'   => 'fa-user',
						'access' => [
							'delete' => [
								'title' => '{lang delete_message}',
								'icon'  => 'fa-trash-o'
							]
						]
					]
				]
			]
		];
	}
}
