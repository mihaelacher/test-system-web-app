let user = {
    // TODO: find a better way to do this
    loadUsers: function () {
        let isSelectable = (window.location.pathname.indexOf('inviteUsers') !== -1);
        let usersTable = $('#usersIndexTable');
        if (usersTable.length) {
            usersTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: '/ajax/users/getUsers',
                    columns: [{
                        data: 'full_name',
                        name: 'full_name'
                    }, {
                        data: 'username',
                        name: 'username'
                    }, {
                        data: 'email',
                        name: 'email'
                    }, {
                        data: 'is_admin',
                        name: 'is_admin'
                    }],
                    select: isSelectable
                }
            });
        }
    },

    // TODO: this function doesn't belong to user.js
    handleUserSelectionOnSubmit: function () {
        let testForm = $('#testParticipationForm');

        testForm.on('submit', function () {
            let selectedIds = [];
            let selectedDataTableRows = $('#usersIndexTable').DataTable().rows({selected: true});

            if (selectedDataTableRows.count()) {
                selectedDataTableRows.data().each(function () {
                    $.each(this, function (index, value) {
                        selectedIds.push(value.id);
                    })
                });

                $('<input />').attr('type', 'hidden')
                    .attr('name', 'selected_user_ids')
                    .attr('value', selectedIds.join(','))
                    .appendTo(testForm);
            }
        });
    },

    addCustomPasswordValidationMethods: function () {
        $.validator.addMethod('hasDigit', function (value) {
            return /\d/.test(value);
        });

        $.validator.addMethod('hasLowercaseLetter', function (value) {
            return /[a-z]/.test(value);
        });

        $.validator.addMethod('hasUppercaseLetter', function (value) {
            return /[A-Z]/.test(value);
        });

        $.validator.addMethod('allowedCharacters', function (value) {
            return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value);
        });
    },

    attachUserFormValidator: function () {
        let rules = {
            'first_name': {required: true},
            'last_name': {required: true},
            'email': {required: true, email: true},
        }

        let messages = {
            'first_name': 'Please, specify first name for user!',
            'last_name': 'Please, specify last name for user!',
            'email': {
                required: 'Please, specify email for user!',
                email: 'Email not in correct format!'
            }
        }

        validator.addFormValidationHandler('usersForm', rules, messages);
    },

    attachChangePasswordValidator: function () {
        let rules = {
            'password': {
                required: true,
                minlength: 10,
                hasDigit: true,
                hasLowercaseLetter: true,
                hasUppercaseLetter: true,
                allowedCharacters: true
            },
            'password_confirmation': {
                required: true,
                equalTo: '#password'
            }
        }

        let messages = {
            'password': {
                required: 'Please, specify password!',
                minlength: 'Please, insert at least 10 characters!',
                hasDigit: 'Password must contain at least one number!',
                hasLowercaseLetter: 'Password must contain at least one lower case letter!',
                hasUppercaseLetter: 'Password must contain at least one upper case letter!',
                allowedCharacters: 'Password contains invalid characters! Allowed are: "!@.-_*"'
            },
            'password_confirmation': {
                required: 'Please, confirm password!',
                equalTo: 'Passwords do not match!'
            }
        }

        validator.addFormValidationHandler('changePasswordForm', rules, messages);
    },

    init: function () {
        this.loadUsers();
        this.handleUserSelectionOnSubmit();
        this.addCustomPasswordValidationMethods();
        this.attachUserFormValidator();
        this.attachChangePasswordValidator();
    }
};
user.init();
