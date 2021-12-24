var validator = {
    addFormValidationHandler: function (formId, rules, messages, highlight, unhighlight, submitHandler) {
        let form = $('#' . formId);
        let formError = $('.alert-danger', form)
        let formSuccess = $('.alert-success', form);

        if (messages === null) {
            messages = undefined;
        }

        if (highlight === undefined || highlight === null) {
            highlight = function (element) {
                $(element).closest('.form-group').addClass('has-error');
            }
        }

        // Definde default unhighlight function.
        if (unhighlight === undefined || unhighlight === null) {
            unhighlight = function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            }
        }

        // Define default submit handler function.
        if (submitHandler === undefined || submitHandler === null) {
            submitHandler = function (form, event) {
                formSuccess.show();
                formError.hide();
              //  form.submit();
                console.log(form);
                event.stopPropagation();
                event.preventDefault();
            }
        }

        form.validate({
            errorElement: 'span', // default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // don't focus the last invalid input
            ignore: '', // validate all fields including form hidden input
            rules: rules,
            messages: messages,
            errorPlacement: function (error, element) { // render error placement for each input type
                // default
                error.insertAfter(element);
            },
            invalidHandler: function (form, validator) {
                if (!validator.numberOfInvalids()) {
                    return;
                }

                formSuccess.hide();
                formError.show();

                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                });
            },
            highlight: highlight,
            unhighlight: unhighlight,
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
            },
            submitHandler: submitHandler
        });
    }
}
