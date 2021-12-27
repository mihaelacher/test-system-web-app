var test = {
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
            console.log(match[1]);
            let ajaxUrl = match === null
                ? '/ajax/questions/getQuestions'
                : '/ajax/tests/' + match[1] + '/getTestQuestions' + (isEditMode ? '?isEdit=1' : '');

            questionsTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: ajaxUrl,
                    columns: utils.getQuestionDatatableCols(),
                    select: isEditMode
                }
            });
            test.handleQuestionSelectionOnSubmit(questionsTable);
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

            $('<input />').attr('type', 'hidden')
                .attr('name', 'selected_question_ids')
                .attr('value', selectedIds.join(','))
                .appendTo(form);
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
    init: function () {
        this.loadTests();
        this.loadTestQuestions();

        this.initDateTimePickers();

        this.attachTestFormValidator();
    }
};
test.init();
