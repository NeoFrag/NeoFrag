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
	}
}
