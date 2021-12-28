var validator = {
    overrideCoreValidationFunction: function () {
        // this is needed in order to validate cloned items with the same name attribute
        $.validator.prototype.checkForm = function (){
            this.prepareForm();
            for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
                    for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                        this.check( this.findByName( elements[i].name )[cnt] );
                    }
                }
                else {
                    this.check( elements[i] );
                }
            }
            return this.valid();
        }
    },

    addFormValidationHandler: function (formId, rules, messages, highlight, unhighlight, submitHandler) {
        let form = $('#' + formId);
        let formError = $('.alert-danger', form)
        let formSuccess = $('.alert-success', form);

        if (messages === null) {
            messages = undefined;
        }

        if (highlight === undefined || highlight === null) {
            highlight = function (element) {
                let jqElement = $(element);

                if (jqElement.is(':checkbox') || jqElement.is(':radio')) {
                    jqElement.closest('.row').addClass('has-error');
                } else {
                    jqElement.closest('.form-group').addClass('has-error');
                }
            }
        }

        // Definde default unhighlight function.
        if (unhighlight === undefined || unhighlight === null) {
            unhighlight = function (element) {
                let jqElement = $(element);

                if (jqElement.is(':checkbox') || jqElement.is(':radio')) {
                    jqElement.closest('.row').removeClass('has-error');
                } else {
                    jqElement.closest('.form-group').removeClass('has-error');
                }
            }
        }

        // Define default submit handler function.
        if (submitHandler === undefined || submitHandler === null) {
            submitHandler = function (form, event) {
                formSuccess.show();
                formError.hide();
                form.submit();
            }
        }

        return form.validate({
            errorElement: 'span', // default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // don't focus the last invalid input
            ignore: '', // validate all fields including form hidden input
            rules: rules,
            messages: messages,
            errorPlacement: function (error, element) { // render error placement for each input type
                let jqElement = $(element);

                if (jqElement.is(':checkbox') || jqElement.is(':radio')) {
                    error.insertBefore(jqElement.closest('.row'));
                } else if (jqElement.hasClass('datetimepicker')) {
                    error.insertAfter(jqElement.closest('.input-group'));
                } else {
                    // default
                    error.insertAfter(jqElement);
                }
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
                label.closest('.row').removeClass('has-error');
                label.closest('.form-group').removeClass('has-error');
            },
            submitHandler: submitHandler
        });
    },

    init: function () {
        this.overrideCoreValidationFunction();
    }
}
validator.init();
