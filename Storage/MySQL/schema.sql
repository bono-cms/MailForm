
/* Mail forms */
DROP TABLE IF EXISTS `bono_module_mailform`;
CREATE TABLE `bono_module_mailform` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `template` varchar(255) NOT NULL COMMENT 'Framework-compliant template view file',
    `seo` varchar(1) NOT NULL,
    `message` LONGTEXT COMMENT 'Message template',
    `captcha` BOOLEAN NOT NULL COMMENT 'Whether this form is protected by CAPTCHA',
    `subject` varchar(255) NOT NULL COMMENT 'Subject with opt.variables',
    `type` SMALLINT NOT NULL COMMENT 'Form type constant',
    `autocomplete` BOOLEAN NOT NULL COMMENT 'Whether autocomplete enabled',
    `flash_position` INT NOT NULL COMMENT 'Flash message position'
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_translations`;
CREATE TABLE `bono_module_mailform_translations` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `web_page_id` INT DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `keywords` TEXT DEFAULT NULL,
    `meta_description` TEXT DEFAULT NULL,
    `flash` TEXT NOT NULL COMMENT 'Optional flash message',
    `terms` TEXT NOT NULL COMMENT 'Optional terms text',

    FOREIGN KEY (id) REFERENCES bono_module_mailform(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Dynamic fields */
DROP TABLE IF EXISTS `bono_module_mailform_fields`;
CREATE TABLE `bono_module_mailform_fields` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Field ID',
    `form_id` INT NOT NULL COMMENT 'Attached form ID',
    `type` SMALLINT NOT NULL COMMENT 'Type constant',
    `order` INT NOT NULL COMMENT 'Sorting order',
    `required` BOOLEAN NOT NULL COMMENT 'Whether this field can be empty',
    `column` SMALLINT COMMENT 'Optional filtering column for rendering',

    FOREIGN KEY (form_id) REFERENCES bono_module_mailform(id) ON DELETE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_fields_translations`;
CREATE TABLE `bono_module_mailform_fields_translations` (
    `id` INT NOT NULL COMMENT 'Field ID',
	`lang_id` INT NOT NULL COMMENT 'Attached lang ID',
    `name` varchar(255) COMMENT 'Field name',
    `hint` TEXT COMMENT 'Hint or placeholder',
    `default` varchar(255) DEFAULT NULL COMMENT 'Optional default value',
    `error` varchar(255) DEFAULT NULL COMMENT 'Error message',

    FOREIGN KEY (id) REFERENCES bono_module_mailform_fields(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Field values */
DROP TABLE IF EXISTS `bono_module_mailform_fields_values`;
CREATE TABLE `bono_module_mailform_fields_values` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Value ID',
    `field_id` INT NOT NULL COMMENT 'Attached field ID',
    `order` INT NOT NULL COMMENT 'Sorting order',

    FOREIGN KEY (field_id) REFERENCES bono_module_mailform_fields(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_fields_values_translations`;
CREATE TABLE `bono_module_mailform_fields_values_translations` (
    `id` INT NOT NULL COMMENT 'Value ID',
    `lang_id` INT NOT NULL COMMENT 'Attached lang ID',
    `value` TEXT COMMENT 'Value',
    `default` varchar(255) COMMENT 'Default state',

    FOREIGN KEY (id) REFERENCES bono_module_mailform_fields_values(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Submit logger */
DROP TABLE IF EXISTS `bono_module_mailform_submits`;
CREATE TABLE `bono_module_mailform_submits` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Submit ID',
    `datetime` DATETIME NOT NULL COMMENT 'Date and time of submission',
    `message` TEXT NOT NULL COMMENT 'Message body',
    `subject` varchar(255) NOT NULL COMMENT 'Message subject',
    `attachments` INT NOT NULL COMMENT 'Attachments count'
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Extra fields */
DROP TABLE IF EXISTS `bono_module_mailform_extra_fields_cat_rel`;
CREATE TABLE `bono_module_mailform_extra_fields_cat_rel` (
    `master_id` INT NOT NULL COMMENT 'MF ID',
    `slave_id` INT NOT NULL COMMENT 'Category ID',

    FOREIGN KEY (master_id) REFERENCES bono_module_mailform(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES bono_module_block_categories(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_extra_fields`;
CREATE TABLE `bono_module_mailform_extra_fields` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `entity_id` INT NOT NULL COMMENT 'MF ID',
    `field_id` INT NOT NULL COMMENT 'Related field_id in block module',
    `value` LONGTEXT NOT NULL COMMENT 'Non-translateable value',

    FOREIGN KEY (entity_id) REFERENCES bono_module_mailform(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES bono_module_block_category_fields(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_mailform_extra_fields_translations`;
CREATE TABLE `bono_module_mailform_extra_fields_translations` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL COMMENT 'Language identificator',
    `value` LONGTEXT NOT NULL,

    FOREIGN KEY (id) REFERENCES bono_module_mailform_extra_fields(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;
