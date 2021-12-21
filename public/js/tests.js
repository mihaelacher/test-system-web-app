var test = {
    loadTests: function () {
        var testsTable = $('#testsIndexTable');
        if (testsTable.length) {
            testsTable.DataTable({
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
                }],
                responsive: true,
                bFilter: false,
                lengthChange: false,
                ordering: false,
                info: false,
            });
        }
    },
    handleQuestionLoadBtn: function () {
        $('#questionsLoadBtn').on('click', function () {
            var btn = $(this);
            $.ajax({
                method: 'GET',
                url: '/ajax/tests/loadQuestions',
                dataType: 'html',
            }).success(function (data) {
                btn.remove();
                $('#questionsTable').html(data);
                question.loadQuestions(true);
                test.handleQuestionSelectionOnSubmit($('#questionsIndexTable'))
            });
        });
    },
    handleQuestionSelectionOnSubmit: function (tableSelector) {
        var testForm = $('#testForm');

        testForm.on('submit', function () {
            var selectedIds = [];
            var selectedDataTableRows = tableSelector.DataTable().rows({selected: true});

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
    loadTestQuestions: function (isSelectable) {
        var questionsTable = $('#testQuestionsIndexTable');
        var testId = $('#js-test-id').val();
        var isEdit = $('#js-is-edit').val();

        if (questionsTable.length) {
            questionsTable.DataTable({
                ajax: '/ajax/tests/getTestQuestions?testId=' + testId + (isEdit ? '&isEditMode=1' : ''),
                columns: question.getQuestionDatatableCols(),
                responsive: true,
                select: isSelectable
            });
            test.handleQuestionSelectionOnSubmit(questionsTable);
        }
    },
    initDateTimePickers: function () {
        $('#from-time-datetimepicker').datetimepicker({ format: 'DD.MM.YYYY HH:mm'});
        $('#to-time-datetimepicker').datetimepicker({ format: 'DD.MM.YYYY HH:mm'});
    },
    init:function () {
        this.loadTests();
        this.handleQuestionLoadBtn();
        this.loadTestQuestions(window.location.pathname.indexOf('edit') !== -1);
        if (window.location.pathname.indexOf('inviteUsers') !== -1) {
            this.initDateTimePickers();
        }
    }
};
test.init();
