ALTER TABLE `nf_permissions_details` CHANGE `entity_id` `entity_id` VARCHAR(100) NOT NULL;
UPDATE `nf_permissions_details` SET `entity_id` = 'admins' WHERE `entity_id` = '1' AND `type` = 'group';
UPDATE `nf_permissions_details` SET `entity_id` = 'members' WHERE `entity_id` = '2' AND `type` = 'group';