
# Mail Form module

Mail form is the **ultimate FREE** form creation tool for Bono CMS. Build various forms and surveys within minutes using a simple interface with no code.

# Features

- Simple and intuitive interface
- Built-in internalization support
- Common dynamic fields support plus extras (checkbox list, radio list)
- File input support. On submit, uploaded files are sent as mail attachments
- Required and non-required validation rule with custom message support
- Sorting order for fields to be rendered
- Regular and AJAX forms
- Input placeholders
- Default values for inputs
- Separation by columns. This optional feature makes it possible to render fields in different blocks on your page.
- Custom flash messages on successful sending
- Protection with CAPTCHA (can be enabled or disabled) and CSRF by default.
- Customizeable subjects and messages with variable placeholders
- Custom templates support
- Form submission logger

# Installation

Nothing extra to do. Can be installed just like any other Bono module. Please refer to [documentation](https://bono-docs.readthedocs.io/en/latest/Modules/Mail%20forms/).

# How to use it?

Well, really it's insanely simply. Just create a form in administration panel with fields, then just render it in template with one line of code!

## In simple forms

Inside a simple form, just include a partial:

    <div>
        <?php $this->loadPartial('mf-form'); ?>
    </div>

Done! Now when you open this page, your form will be rendered for you!

## In AJAX forms

Unlike regular forms, AJAX-forms are forms have no dedicated page. They might be globally located in your layout.

Anywhere in your template, just call `<?= $form->render($id); ?>` where `$id` is the ID of the form.


# Styling forms

By default all inputs are styled according to Twitter Bootstrap.

However, you are completely free to design forms and fields they way you want. No tightly-coupled CSS classes and bad things like that.

# Where does it send messages from filled forms?

When sending, it uses global email and its configuration that you've set in system configuration.


# What about conditional logic?

That will be implemented in future versions.

If you need to show/hide some field based on value of another field, then write several lines of JavaScript code using jquery. Come on man, Its really that simple!


# Bug reports

If you encounter a bug, please report an issue here, providing as much as possible information on how to reproduce that bug. 

Please don't open an issue if that bug related to security. Instead send an email directly to developer.

# License

Licensed under the same license as Bono CMS