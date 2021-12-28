const selectedQuestionsCacheKey = 'selected_question_ids';
const selectedUsersCacheKey = 'selected_user_ids';

let test = {

    loadTests: function () {
        let testsTable = $('#testsIndexTable');

        if (testsTable.length) {
            testsTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: '/ajax/tests/getTests',
                    columns: [{
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'intro_text',
                        name: 'intro_text'
                    }, {
                        data: 'max_duration',
                        name: 'max_duration'
                    }]
                }
            });
        }
    },

    loadTestQuestions: function () {
        let questionsTable = $('#questionsIndexTable');

        if (questionsTable.length) {
            let currentUrl = window.location.pathname;
            let isEditMode = currentUrl.indexOf('edit') !== -1;
            let match = /tests\/(\d+)/.exec(window.location.pathname);

            let ajaxUrl = match === null
                ? '/ajax/questions/getQuestions'
                : '/ajax/tests/' + match[1] + '/getTestQuestions' + (isEditMode ? '?isEdit=1' : '');

            let selectedQuestionIds = utils.getDataFromCache(selectedQuestionsCacheKey);

            questionsTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: ajaxUrl,
                    columns: utils.getQuestionDatatableCols(),
                    select: isEditMode || currentUrl.indexOf('create') !== -1,
                    rowCallback: function (row, data) {
                        if (typeof selectedQuestionIds !== undefined) {
                            if ($.inArray(data.id, selectedQuestionIds) !== -1) {
                                $(row).addClass('selected');
                            }
                        }
                    }
                }
            });
        }
    },

    handleQuestionSelectionOnSubmit: function (form) {
        let selectedIds = [];
        let selectedDataTableRows = $('#questionsIndexTable').DataTable().rows('.selected');

        if (selectedDataTableRows.count()) {
            selectedDataTableRows.data().each(function () {
                $.each(this, function (index, value) {
                    selectedIds.push(value.id);
                })
            });

            selectedIds = [...new Set(selectedIds)];

            $('<input />').attr('type', 'hidden')
                .attr('name', 'selected_question_ids')
                .attr('value', selectedIds.join(','))
                .appendTo(form);

            utils.putDataInCache(selectedQuestionsCacheKey, selectedIds);
        }

        form.submit();
    },

    initUsersDatatable: function () {
        let usersTable = $('#usersIndexTable');

        if (usersTable.length) {
            let selectedUserIds = utils.getDataFromCache(selectedUsersCacheKey);

            usersTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: '/ajax/users/getUsers',
                    columns: utils.getUsersDatatableCols(),
                    select: true,
                    rowCallback: function (row, data) {
                        if (typeof selectedUserIds !== undefined) {
                            if ($.inArray(data.id, selectedUserIds) !== -1) {
                                $(row).addClass('selected');
                            }
                        }
                    }
                }
            });
        }
    },

    handleUserSelectionOnSubmit: function (form) {
        let selectedIds = [];
        let selectedDataTableRows = $('#usersIndexTable').DataTable().rows({selected: true});

        if (selectedDataTableRows.count()) {
            selectedDataTableRows.data().each(function () {
                $.each(this, function (index, value) {
                    selectedIds.push(value.id);
                })
            });

            selectedIds = [...new Set(selectedIds)];

            $('<input />').attr('type', 'hidden')
                .attr('name', 'selected_user_ids')
                .attr('value', selectedIds.join(','))
                .appendTo(form);

            utils.putDataInCache(selectedUsersCacheKey, selectedIds);
        }

        form.submit();
    },

    initDateTimePickers: function () {
        if (window.location.pathname.indexOf('inviteUsers') !== -1) {
            $('#from-time-datetimepicker').datetimepicker({format: 'DD.MM.YYYY HH:mm'});
            $('#to-time-datetimepicker').datetimepicker({format: 'DD.MM.YYYY HH:mm'});
        }
    },

    attachTestFormValidator: function () {
        let rules = {
            'name': {required: true},
            'max_duration': {required: true, digits: true}
        }

        let messages = {
            'name': 'Please, specify name!',
            'max_duration': {
                required: 'Please, specify max duration!'
            }
        }

        validator.addFormValidationHandler('testForm', rules, messages, null,
            null, test.handleQuestionSelectionOnSubmit);
    },

    attachInviteUsersFormValidation: function () {
        let rules = {
            'active_from': {
                required: true,
                validFormat: true,
                beforeDate: '#activeTo'
            },
            'active_to': {
                required: true,
                validFormat: true,
                afterDate: '#activeFrom'
            }
        };

        let messages = {
            'active_from': {
                required: 'Please, specify start time.'
            },
            'active_to': {
                required: 'Please, specify end time.'
            }
        };

        validator.addFormValidationHandler('testParticipationForm', rules, messages,
            null, null, test.handleUserSelectionOnSubmit)
    },

    addCustomDateValidationMethods: function () {
        $.validator.addMethod('validFormat', function (value, element) {
            return moment(value, 'DD.MM.YYYY HH:ii').isValid();
        }, 'Date time format is not valid.');

        $.validator.addMethod('afterDate', function (value, element, params) {
            if ($(params).val() === '') {
                return  true;
            }
            return new Date(value) > new Date($(params).val());
        }, 'Must be before active from date.');

        $.validator.addMethod('beforeDate', function (value, element, params) {
            if ($(params).val() === '') {
                return  true;
            }
            return new Date(value) < new Date($(params).val());
        }, 'Must be after active to date.');
    },

    init: function () {
        this.loadTests();
        this.loadTestQuestions();
        this.initUsersDatatable();

        this.initDateTimePickers();

        this.addCustomDateValidationMethods();
        this.attachTestFormValidator();
        this.attachInviteUsersFormValidation();
    }
};
test.init();
