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

    loadTestQuestions: function (isSelectable) {
        let questionsTable = $('#questionsIndexTable');
        let testId = $('#js-test-id').val();
        let url = '/ajax/tests/getTestQuestions';

        if (testId) {
            url.searchParams.append('testId', testId);
        }

        if (questionsTable.length) {
            questionsTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: url,
                    columns: utils.getQuestionDatatableCols(),
                    select: isSelectable
                }
            });
            test.handleQuestionSelectionOnSubmit(questionsTable);
        }
    },

    handleQuestionSelectionOnSubmit: function () {
        let testForm = $('#testForm');

        testForm.on('submit', function () {
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
                    .appendTo(testForm);
            }
        });
    },

    initDateTimePickers: function () {
        $('#from-time-datetimepicker').datetimepicker({format: 'DD.MM.YYYY HH:mm'});
        $('#to-time-datetimepicker').datetimepicker({format: 'DD.MM.YYYY HH:mm'});
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

        validator.addFormValidationHandler('testForm', rules, messages);
    },
    init: function () {
        this.loadTests();
        this.handleQuestionSelectionOnSubmit();
        this.loadTestQuestions(window.location.pathname.indexOf('edit') !== -1);
        if (window.location.pathname.indexOf('inviteUsers') !== -1) {
            this.initDateTimePickers();
        }
        this.attachTestFormValidator();
    }
};
test.init();
