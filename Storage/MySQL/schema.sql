
DROP TABLE IF EXISTS `bono_module_mailform`;
CREATE TABLE `bono_module_mailform` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `template` varchar(255) NOT NULL COMMENT 'Framework-compliant template view file',
    `seo` varchar(1) NOT NULL,
    `message` LONGTEXT COMMENT 'Message template'
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_translations`;
CREATE TABLE `bono_module_mailform_translations` (

    `id` INT NOT NULL,
	`lang_id` INT NOT NULL,
	`web_page_id` INT NOT NULL,
    `name` varchar(255) NOT NULL,
	`title` varchar(255) NOT NULL,
	`description` LONGTEXT NOT NULL,
	`keywords` text NOT NULL,
    `meta_description` TEXT NOT NULL

) DEFAULT CHARSET = UTF8;