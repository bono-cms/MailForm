
DROP TABLE IF EXISTS `bono_module_mailform`;
CREATE TABLE `bono_module_mailform` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `template` varchar(255) NOT NULL COMMENT 'Framework-compliant template view file',
    `seo` varchar(1) NOT NULL,
    `message` LONGTEXT COMMENT 'Message template',
    `captcha` BOOLEAN NOT NULL COMMENT 'Whether this form is protected by CAPTCHA'
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

/* Dynamic fields */
DROP TABLE IF EXISTS `bono_module_mailform_fields`;
CREATE TABLE `bono_module_mailform_fields` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Field ID',
    `form_id` INT NOT NULL COMMENT 'Attached form ID',
    `type` SMALLINT NOT NULL COMMENT 'Type constant',
    `order` INT NOT NULL COMMENT 'Sorting order',
    `required` BOOLEAN NOT NULL COMMENT 'Whether this field can be empty'

    FOREIGN KEY (form_id) REFERENCES bono_module_mailform(id) ON DELETE CASCADE

) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_fields_translations`;
CREATE TABLE `bono_module_mailform_fields_translations` (
    `id` INT NOT NULL COMMENT 'Field ID',
	`lang_id` INT NOT NULL COMMENT 'Attached lang ID',
    `name` varchar(255) COMMENT 'Field name',
    `hint` TEXT COMMENT 'Hint or placeholder',
    `default` varchar(255) DEFAULT NULL COMMENT 'Optional default value',
    `error` varchar(255) DEFAULT NULL COMMENT 'Error message',

    FOREIGN KEY (id) REFERENCES bono_module_mailform_fields(id) ON DELETE CASCADE
) DEFAULT CHARSET = UTF8;


/* Field values */
DROP TABLE IF EXISTS `bono_module_mailform_fields_values`;
CREATE TABLE `bono_module_mailform_fields_values` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Value ID',
    `field_id` INT NOT NULL COMMENT 'Attached field ID',
    `order` INT NOT NULL COMMENT 'Sorting order',

    FOREIGN KEY (field_id) REFERENCES bono_module_mailform_fields(id) ON DELETE CASCADE
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_fields_values_translations`;
CREATE TABLE `bono_module_mailform_fields_values_translations` (
    `id` INT NOT NULL COMMENT 'Value ID',
    `lang_id` INT NOT NULL COMMENT 'Attached lang ID',
    `value` varchar(255) COMMENT 'Value',

    FOREIGN KEY (id) REFERENCES bono_module_mailform_fields_values(id) ON DELETE CASCADE
) DEFAULT CHARSET = UTF8;
