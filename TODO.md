TODO List
=========

 * Sorting order must be auto-incrementing when adding fields by default
 * Add client-side events (on form submited, etc)
 * What to do after form submit (Refresh the page showing flash message, perform a GET/POST redirect)
 * Individual field render to avoid loop
 * User analytic fields (IP, Browser, Device, Country) in logger
 * Add support optgroup for select inputs
 * Dynamic fields don't work on AJAX-forms
 * Ability to override email
 * Add optional back button in forms
 * Shared fields for forms. For example - different forms might require one shared dropdown
 * Conditional logic for fields. Right now conditionals handled manually
 * Add new type - text batch inputs
 * Ability to turn forms into wizards
 * Ability to save entered values in localStorage until a form is submitted
 * Hidden inputs fields should be able to populate its values from GET parameters
 * Ability to get Geolocation, IP data and other related information from submissions
 * Form templates
 * Limitations by date or number of submits
 * New conception of Payment forms (to be integrated with Payment module)
 * Add rating field
 * Add survey field
 * Add options on what to do after submissions
 * Select field should be able to select multiple values
 * Checkboxes should support trees
 * When adding new language from the system - it's not being saved in forms. Fix this bug.

Bugs:
 
 * Validation on checkbox not working
 * Checkboxes's value not being sorted by their order
 

Migration from legacy to newer:

    ALTER TABLE bono_module_mailform ADD COLUMN `labeled` BOOLEAN NOT NULL DEFAULT 1 COMMENT 'Whether to render labels';
    ALTER TABLE bono_module_mailform_fields ADD COLUMN `row` SMALLINT DEFAULT 0 COMMENT 'Optional row number';
    ALTER TABLE bono_module_mailform_fields DROP COLUMN `column`;