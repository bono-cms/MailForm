CHANGELOG
=========

1.2
---

 * Added missing values in breadcrumbs
 * Disabled autocomplete in forms
 * Added form submission logger
 * Introduced conception of regular and AJAX forms
 * Added shared form partial template. As of now, there's no need to write boilerplate code in site templates
 * Ability to set custom subjects
 * Added option to enable or disable CAPTCHA
 * Added dynamic field functionality. Dropped previous way of handling static fields.
 * Support complete internalization
 * Removed menu widget
 * Fixed issue with quote escaping
 * Added `message` text field. Since now messages can be easily edited right on the edit page
 * Added support for partial forms. Since now a form can be rendered anywhere in template by calling `$form->render($id)`
 * Added `name` attribute
 * Added support for table prefix
 * Changed module icon
 * Improved internal structure

1.1
---

 * Improved internals

1.0
---

 * First public version