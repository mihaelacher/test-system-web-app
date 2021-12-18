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
                responsive: true
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
            });
        });
    },
    handleQuestionSelectionOnSubmit: function () {
        var testForm = $('#testCreateForm');

        testForm.on('submit', function () {
            var selectedIds = [];
            var selectedDataTableRows = $('#questionsIndexTable').DataTable().rows({selected: true});

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
    init:function () {
        this.loadTests();
        this.handleQuestionLoadBtn();
        this.handleQuestionSelectionOnSubmit();
    }
};
test.init();
