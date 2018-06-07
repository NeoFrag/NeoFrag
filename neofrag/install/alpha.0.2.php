<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_2 extends NeoFrag
{
	public function up()
	{
		$this->load = NeoFrag();

		$this	->network('https://neofrag.download')
				->stream($file = 'cache/monitoring/neofrag.zip');

		if ($zip = zip_open($file))
		{
			while ($zip_entry = zip_read($zip))
			{
				if (preg_match('#^('.implode('|', ['addons/', 'css/', 'fonts/', 'images/', 'js/', 'config/neofrag.php']).')#', $entry_name = zip_entry_name($zip_entry)))
				{
					if (substr($entry_name, -1) == '/')
					{
						dir_create($entry_name);
					}
					else if (zip_entry_open($zip, $zip_entry, 'r'))
					{
						file_put_contents($entry_name, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
					}
				}

				zip_entry_close($zip_entry);
			}

			zip_close($zip);
		}

		@unlink($file);

		@mkdir('logs', 0775, TRUE);

		foreach ([
				'authenticators',
				'neofrag/classes',
				'neofrag/databases',
				'neofrag/lang',
				'neofrag/modules',
				'neofrag/themes',
				'neofrag/views/comments',
				'neofrag/widgets'
			] as $dir)
		{
			dir_remove($dir);
		}

		$this->db	->where('name', 'error')
					->delete('nf_settings_addons');

		$this->db->insert('nf_settings_addons', [
			'name' => 'admin',
			'type' => 'theme'
		]);

		//Addon
		$this->db	->execute('CREATE TABLE `nf_addon_type` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8')
					->execute('INSERT INTO `nf_addon_type` (`id`, `name`) VALUES
						(1, \'module\'),
						(2, \'theme\'),
						(3, \'widget\'),
						(4, \'language\'),
						(5, \'authenticator\')')
					->execute('CREATE TABLE `nf_addon` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `type_id` int(11) unsigned DEFAULT NULL,
					  `name` varchar(100) NOT NULL,
					  `data` text,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `name` (`name`,`type_id`),
					  KEY `type_id` (`type_id`),
					  CONSTRAINT `nf_addon_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `nf_addon_type` (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8')
					->execute('INSERT INTO `nf_addon` VALUES(NULL, NULL, \'authenticator\', NULL)')
					->execute('ALTER TABLE `nf_access` DROP FOREIGN KEY `nf_access_ibfk_1`')
					->execute('ALTER TABLE `nf_comments` DROP FOREIGN KEY `nf_comments_ibfk_3`')
					->execute('ALTER TABLE `nf_votes` DROP FOREIGN KEY `nf_votes_ibfk_1`')
					->execute('ALTER TABLE `nf_widgets` DROP FOREIGN KEY `nf_widgets_ibfk_1`')
					->execute('ALTER TABLE `nf_users_auth` DROP FOREIGN KEY `nf_users_auth_ibfk_2`')
					->execute('ALTER TABLE `nf_gallery_categories_lang` DROP FOREIGN KEY `nf_gallery_categories_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_gallery_lang` DROP FOREIGN KEY `nf_gallery_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_games_lang` DROP FOREIGN KEY `nf_games_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_groups_lang` DROP FOREIGN KEY `nf_groups_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_news_categories_lang` DROP FOREIGN KEY `nf_news_categories_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_news_lang` DROP FOREIGN KEY `nf_news_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_pages_lang` DROP FOREIGN KEY `nf_pages_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_partners_lang` DROP FOREIGN KEY `nf_partners_lang_ibfk_2`')
					->execute('ALTER TABLE `nf_teams_lang` DROP FOREIGN KEY `nf_teams_lang_ibfk_1`')
					->execute('ALTER TABLE `nf_users` DROP FOREIGN KEY `nf_users_ibfk_1`');

		$types = [
			'module'   => 1,
			'theme'    => 2,
			'widget'   => 3
		];

		$allowed = [
			'module' => [
				'access',
				'addons',
				'admin',
				'awards',
				'gallery',
				'talks',
				'comments',
				'contact',
				'error',
				'members',
				'events',
				'games',
				'forum',
				'live_editor',
				'monitoring',
				'news',
				'pages',
				'partners',
				'recruits',
				'search',
				'settings',
				'statistics',
				'teams',
				'user'
			],
			'theme' => [
				'default',
				'admin'
			],
			'widget' => [
				'navigation',
				'error',
				'news',
				'events',
				'forum',
				'partners',
				'gallery',
				'recruits',
				'header',
				'search',
				'html',
				'slider',
				'breadcrumb',
				'members',
				'talks',
				'module',
				'teams',
				'awards',
				'user'
			]
		];

		foreach ($this->db->from('nf_settings_addons')->order_by('type', 'name')->get() as $addon)
		{
			if (!in_array($addon['name'], $allowed[$addon['type']]))
			{
				continue;
			}

			$this->db->insert('nf_addon', [
				'type_id' => $types[$addon['type']],
				'name'    => $addon['name'],
				'data'    => $addon['type'] != 'theme' ? serialize([
					'enabled' => (bool)$addon['is_enabled']
				]) : NULL
			]);
		}

		foreach ($this->db->from('nf_settings_languages')->get() as $lang)
		{
			$this->db->insert('nf_addon', [
				'type_id' => 4,
				'name'    => $lang['code'],
				'data'    => serialize([
					'order'   => $lang['order'],
					'enabled' => TRUE
				])
			]);
		}

		foreach ($this->db->from('nf_settings_authenticators')->get() as $auth)
		{
			$settings = unserialize($auth['settings']);

			if (!$settings)
			{
				$settings = $auth['name'] == 'steam' ? ['key' => ''] : ['id' => '', 'secret' => ''];
			}

			$this->db->insert('nf_addon', [
				'type_id' => 5,
				'name'    => $auth['name'],
				'data'    => serialize([
					'order'   => $auth['order'],
					'enabled' => (bool)$auth['is_enabled'],
					'dev'     => $settings,
					'prod'    => $settings
				])
			]);
		}

		$this->db	->execute('DROP TABLE nf_settings_addons')
					->execute('DROP TABLE nf_settings_authenticators')
					->execute('DROP TABLE nf_settings_languages')
					->execute('DROP TABLE nf_settings_smileys');

		//Crawlers
		$this->db->execute('DROP TABLE IF EXISTS `nf_crawlers`');

		//I18n
		$this->db->execute('CREATE TABLE `nf_i18n` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `lang_id` int(10) unsigned NOT NULL,
		  `model` varchar(100) DEFAULT NULL,
		  `model_id` int(10) unsigned DEFAULT NULL,
		  `name` varchar(100) NOT NULL,
		  `value` text NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `lang_id` (`lang_id`,`model`,`model_id`,`name`) USING BTREE,
		  KEY `lang_id_2` (`lang_id`),
		  KEY `model` (`model`),
		  KEY `model_id` (`model_id`),
		  KEY `name` (`name`),
		  CONSTRAINT `nf_i18n_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `nf_addon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		//Log I18n
		$this->db->execute('CREATE TABLE `nf_log_i18n` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `language` char(2) NOT NULL,
		  `key` char(32) NOT NULL,
		  `locale` text NOT NULL,
		  `file` varchar(100) NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `language` (`language`,`key`,`file`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		//Log DB
		$this->db->execute('CREATE TABLE `nf_log_db` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `action` enum(\'0\',\'1\',\'2\') NOT NULL,
		  `model` varchar(100) NOT NULL,
		  `primaries` varchar(100) DEFAULT NULL,
		  `data` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		//Tracking
		$this->db->execute('CREATE TABLE IF NOT EXISTS `nf_tracking` (
		  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		  `user_id` int(10) UNSIGNED NOT NULL,
		  `model` varchar(100) NOT NULL,
		  `model_id` int(10) UNSIGNED DEFAULT NULL,
		  `date` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `user_id` (`user_id`,`model`,`model_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

		//Vote
		$this->db->execute('DROP TABLE nf_votes');

		//Debug
		$this->db->where('name', 'nf_debug')->delete('nf_settings');

		//Dispositions
		$replacement = [
			'O:3:"Row"'                                => 'O:27:"NF\\NeoFrag\\Displayables\\Row"',
			'O:3:"Col"'                                => 'O:27:"NF\\NeoFrag\\Displayables\\Col"',
			'O:12:"Panel_widget"'                      => 'O:30:"NF\\NeoFrag\\Displayables\\Widget"',
			"s:12:\"\0*\0_children\""                  => "s:9:\"\0*\0_array\"",
			'#s:8:"\0\*\0_size";s:(\d):"col-md-(\d{1,2})"#' => function($matches){
				return "s:8:\"\0*\0_size\";s:".($matches[1] - 3).":\"col-".$matches[2]."\"";
			}
		];

		$update_disposition = function($serialized) use (&$replacement){
			$result = $serialized;

			foreach ($replacement as $pattern => $callback)
			{
				$result = call_user_func_array(is_a($callback, 'closure') ? 'preg_replace_callback' : 'str_replace', [$pattern, $callback, $result]);
			}

			return unserialize($result = "O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";".$result."}") !== FALSE ? $result : $serialized;
		};

		foreach ($this->db->from('nf_dispositions')->get() as $disposition)
		{
			$this->db	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => $update_disposition($disposition['disposition'])
						]);
		}

		//Config
		$this->db	->where('site', 'default')
					->update('nf_settings', [
						'site' => ''
					]);

		//Sessions
		$this->db	->execute('RENAME TABLE `nf_sessions` TO `nf_session`')
					->execute('ALTER TABLE `nf_session` CHANGE `session_id` `id` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_session` DROP `ip_address`, DROP `host_name`, DROP `is_crawler`')
					->execute('ALTER TABLE `nf_session` CHANGE `remember_me` `remember` ENUM(\'0\',\'1\') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'0\' AFTER user_id')
					->execute('ALTER TABLE `nf_session` CHANGE `user_data` `data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_session` ADD PRIMARY KEY(`id`)')
					->execute('ALTER TABLE `nf_session` DROP INDEX session_id');

		//Sessions History
		$this->db	->execute('ALTER TABLE `nf_sessions_history` DROP FOREIGN KEY `nf_sessions_history_ibfk_2`')
					->execute('ALTER TABLE `nf_sessions_history` DROP `session_id`')
					->execute('RENAME TABLE `nf_sessions_history` TO `nf_session_history`')
					->execute('ALTER TABLE `nf_session_history` CHANGE `authenticator` `auth` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL AFTER user_agent');

		foreach ($this->db->from('nf_session_history')->where('auth <>', '')->get() as $session)
		{
			$this->db	->where('id', $session['id'])
						->update('nf_session_history', [
							'auth' => serialize([
								'authentificator' => $session['auth'],
								'name'            => '',
								'avatar'          => ''
							])
						]);
		}

		//User
		$this->db	->execute('ALTER TABLE `nf_users` CHANGE `user_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('ALTER TABLE `nf_users` ADD `data` TEXT NOT NULL AFTER `language`')
					->execute('RENAME TABLE `nf_users` TO `nf_user`')
					->execute('RENAME TABLE `nf_users_profiles` TO `nf_user_profile`')
					->execute('ALTER TABLE `nf_user_profile` ADD `cover` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `avatar`')
					->execute('ALTER TABLE `nf_user_profile` ADD `country` VARCHAR(100) NOT NULL AFTER `sex`')
					->execute('ALTER TABLE `nf_user_profile` ADD `linkedin` VARCHAR(100) NOT NULL AFTER `website`, ADD `github` VARCHAR(100) NOT NULL AFTER `linkedin`, ADD `instagram` VARCHAR(100) NOT NULL AFTER `github`, ADD `twitch` VARCHAR(100) NOT NULL AFTER `instagram`');

		$this->db	->execute('ALTER TABLE `nf_users_keys` DROP FOREIGN KEY nf_users_keys_ibfk_2')
					->execute('RENAME TABLE `nf_users_keys` TO `nf_user_token`')
					->execute('ALTER TABLE `nf_user_token` CHANGE `key_id` `id` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_user_token` DROP `session_id`, DROP `date`')
					->execute('CREATE TABLE `nf_user_auth` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) unsigned NOT NULL,
					  `authenticator_id` int(11) unsigned NOT NULL,
					  `key` varchar(100) NOT NULL,
					  `username` varchar(100) DEFAULT NULL,
					  `avatar` varchar(100) DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `user_id` (`user_id`,`authenticator_id`,`key`),
					  KEY `authenticator_id` (`authenticator_id`),
					  CONSTRAINT `nf_user_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `nf_user_auth_ibfk_2` FOREIGN KEY (`authenticator_id`) REFERENCES `nf_addon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8');

		foreach ($this->db->from('nf_users_auth')->get() as $auth)
		{
			$this->db	->insert('nf_user_auth', [
							'user_id'          => $auth['user_id'],
							'authenticator_id' => $this->db->select('id')->from('nf_addon')->where('name', $auth['authenticator'])->where('type_id', 5)->row(),
							'key'              => $auth['id']
						]);
		}

		$this->db->execute('DROP TABLE nf_users_auth');

		//File
		$this->db	->execute('ALTER TABLE `nf_files` CHANGE `file_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_files` TO `nf_file`');

		//Talks
		$this->db	->execute('ALTER TABLE nf_talks CONVERT TO CHARACTER SET utf8')
					->execute('ALTER TABLE nf_talks_messages CONVERT TO CHARACTER SET utf8');

		//Comment
		$this->db	->execute('ALTER TABLE `nf_comments` CHANGE `comment_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_comments` TO `nf_comment`');

		//Admin
		$this->db	->where('widget_id', 1)
					->delete('nf_widgets');

		//Widget navigation
		foreach ($this->db->from('nf_widgets')->where('widget', 'navigation')->get() as $nav)
		{
			$settings = unserialize($nav['settings']);
			$display = $settings['display'];
			unset($settings['display']);

			$values = [
				'settings' => serialize($settings)
			];

			if (!$display)
			{
				$values['type'] = 'vertical';
			}

			$this->db	->where('widget_id', $nav['widget_id'])
						->update('nf_widgets', $values);
		}

		//Copyright
		$this->config('nf_copyright', utf8_htmlentities('Copyright {copyright} {year} {name}, tous droits réservés <div class="pull-right">Propulsé par {neofrag}</div>'));
		$this->db->insert('nf_addon', [
			'type_id' => 3,
			'name'    => 'copyright',
			'data'    => serialize([
				'enabled' => TRUE
			])
		]);

		//Theme Default
		$this->config('nf_theme_color', '#2b373a');
	}
}
