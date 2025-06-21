Form module
=====

The Form module lets you create pages on your website that send emails directly to your inbox. It's ideal for contact forms and application forms, making it easy for users to get in touch or submit documents such as resumes.

## Features

-   **Simple and intuitive interface** – Easy to configure and user-friendly.
-   **Built-in internationalization support** – Fully compatible with multilingual websites.
    
-   **Support for common dynamic fields** – Includes text inputs, checkboxes, radio buttons, and more.
    
-   **Advanced field types** – Includes checkbox lists and radio button lists.
    
-   **File upload support** – Uploaded files are automatically attached to the email on form submission.
    
-   **Flexible validation rules** – Define required or optional fields with support for custom error messages.
    
-   **Custom field order** – Easily control the display order of form fields.
    
-   **Support for both regular and AJAX-based submissions** – Improve user experience with seamless form handling.
    
-   **Input placeholders and default values** – Provide helpful context or pre-fill fields as needed.
    
-   **Optional column-based layout** – Render form fields in separate blocks or columns for better organization.
    
-   **Customizable flash messages** – Display user-friendly success messages after submission.
    
-   **Flash message positioning** – Choose to display messages at the top or bottom of the form.
    
-   **Robust security** – Includes CAPTCHA (optional) and CSRF protection by default.
    
-   **Customizable email content** – Use variable placeholders to personalize subjects and messages.
    
-   **Template support** – Create and apply custom form templates for full design flexibility.
    
-   **Form submission logging** – Track and store form submissions for auditing or record-keeping.

## How to use it?

Simply create a form in the administration panel by adding the desired fields—then render it in your template with a single line of code:

    <div>
        <?php $this->loadPartial('mf-form'); ?>
    </div>

That's it! Now, when you open the page, your form will be automatically rendered.

## URL Generation

To generate a URL for a form by its ID (assuming the form ID is 1), use:

    <a href="<?= $cms->createUrl(1, 'MailForm'); ?>">View form</a>

## AJAX forms


AJAX forms can be placed anywhere within your template.
To render the AJAX form, simply call it like this providing form id:

    <?= $form->render($id); ?>


## Styling forms

However, you're free to customize the design as you see fit—there are no restrictive styles. You have full control over the form's appearance through your own CSS.

## Where are submissions sent?

When sending, it uses the global email and its configuration as defined in the system settings.

## Conditional logic

This feature will be available in future versions soon.

In the meantime, if you need to show or hide fields based on the value of another, simply add a few lines of JavaScript using jQuery. It's that easy!