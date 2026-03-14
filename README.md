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

Simply create a form in the administration panel by adding the desired fields — then render it in your template with a single line of code:

    <div>
        <?php $this->loadPartial('mf-form'); ?>
    </div>

That's it! Now, when you open the page, your form will be automatically rendered.

## Customizing options

You can override the default attributes by passing an options array to the partial. This uses recursive merging internally, so you only need to define the specific keys you wish to change:

    <?php $this->loadPartial('mf-form', [
        'options' => [
            'input' => [
                'class' => 'form-control'
            ],
            'submit' => [
                'text'  => 'Submit',
                'class' => 'btn btn-primary'
            ],
            'reset' => [
                'enabled' => false,
                'text'    => 'Reset',
                'class'   => 'btn btn-secondary'
            ]
        ]
    ]); ?>

The system will automatically merge these with the defaults, preserving any attributes you haven't explicitly overridden.

**Example**

You can easily override default classes and button text. For instance, to use large inputs and a wider submit button, pass the following options:

    <?php $this->loadPartial('mf-form', [
        'options' => [
            'input' => [
                'class' => 'form-control form-control-lg'
            ],
            'submit' => [
                'text'  => 'Submit now',
                'class' => 'btn btn-primary px-4'
            ]
        ]
    ]); ?>


## Rows and columns

The module supports a dynamic grid system. If you want to render inputs in columns (side-by-side), simply define a Row number for each field in the administration panel.

Fields with the same row number will be automatically grouped into a single Bootstrap row. For example, assigning "1" to three different fields will render them in a 3-column layout.

## URL Generation

To generate a URL for a form by its ID (assuming the form ID is 1), use:

    <a href="<?= $cms->createUrl(1, 'MailForm'); ?>">View form</a>

## AJAX forms

The module supports asynchronous submissions, allowing you to place forms anywhere in your layout without triggering a page reload. To render an AJAX-enabled form, use the render method and provide the specific form ID:

    <?= $form->render($id); ?>

This method automatically initializes the necessary JavaScript handlers to process the submission in the background and display success or error messages inline.

**Passing variables and options**

The `render()` method also accepts a second optional argument: an array of variables. This allows you to pass custom data or override form options (like button classes) directly from your template:

    <?= $form->render($id, [
        'options' => [
            'submit' => [
                'class' => 'btn w-lg-fit-content btn-light btn-sm px-5 py-2 me-xxl-4'
            ]
        ]
    ]); ?>

This approach is highly flexible, as any key passed in this array becomes available within the main form template, enabling on-the-fly customization for specific pages.

## Form submissions

Form submissions are routed to the global system email address. The module uses the SMTP or mail configuration defined in your core system settings to ensure reliable delivery.

## Conditional logic

Dynamic field visibility (showing or hiding fields based on user input) is planned for a future release.

In the meantime, you can easily implement this functionality manually. Since the form is rendered with standard IDs and classes, a few lines of jQuery or Vanilla JavaScript will allow you to toggle field visibility based on your specific requirements.