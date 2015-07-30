ALTER TABLE `nf_sessions` ADD `is_crawler` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `user_id`;
DROP TABLE IF EXISTS `nf_crawlers`;
CREATE TABLE IF NOT EXISTS `nf_crawlers` (
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;