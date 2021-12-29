let user = {

    loadUsers: function () {
        let usersTable = $('#usersIndexTable');

        if (usersTable.length) {
            let columns = utils.getUsersDatatableCols();

            let operationsCol = {
                data: 'operations',
                name: 'operations'
            }
            columns.push(operationsCol);

            usersTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: '/ajax/users/getUsers?showOperations=1',
                    columns: columns,
                }
            });
        }
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

        this.addCustomPasswordValidationMethods();
        this.attachUserFormValidator();
        this.attachChangePasswordValidator();
    }
};
user.init();
