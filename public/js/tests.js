var testCountDownTimer = 0;
var timeRemainingInSec = $('#timeRemainingInSec').val();
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
    handleSubmitTestExecution: function () {
        var form = $('#executionForm');

        if (form.length) {
            var questionAnswers = [];

            form.on('submit', function () {
                $('.js-question-answers').each(function () {
                    $this = $(this);
                    if ($this.is(':checked')) {
                        var questionId = $this.data('question_id');
                        var answerId = $this.data('answer_id');

                        if (!questionAnswers[questionId]) {
                            questionAnswers[questionId] = [];
                        }
                        // TODO: remove empty entries
                        questionAnswers[questionId].push(answerId);
                    }
                })
                $('<input />').attr('type', 'hidden')
                    .attr('name', 'question_answers')
                    .attr('value', JSON.stringify(questionAnswers))
                    .appendTo(form);
            });
        }
    },
    loadTestExecutions: function () {
        var testExecutionsTable = $('#testExecutionsIndexTable');
        if (testExecutionsTable.length) {
            testExecutionsTable.DataTable({
                ajax: '/ajax/tests/getTestExecutions',
                columns: [{
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'start_time',
                    name: 'start_time'
                }, {
                    data: 'end_time',
                    name: 'end_time'
                }, {
                    data: 'result_points',
                    name: 'result_points'
                }],
                responsive: true
            });
        }
    },
    initAnsweredQuestionHandler: function () {
        $('.js-question-answers').on('change', function () {
            var $this = $(this);
            var testExecutionId = $('#testExecutionId').val();
            var data = {
                _token: $("input[name='_token']").val(),
                questionId: $this.data('question_id'),
            }

            if ($this.is(':checkbox')) {
                var isChecked = $this.is(':checked');
                data['answerId'] = $this.data('answer_id');
                data['isChecked'] = isChecked ? 1 : 0;

                test.triggerQuestionAnswerAjaxCall(testExecutionId, data, $this, isChecked);
            } else {
                data['inputValue'] = $this.val();

                test.triggerOpenQuestionAjaxCall(testExecutionId, data, $this);
            }
        });
    },
    triggerOpenQuestionAjaxCall: function (testExecutionId, data, element) {
        $.ajax({
            method: 'POST',
            url: '/ajax/tests/execute/submitOpenQuestion/' + testExecutionId,
            data: data
        }).success(function (response) {
            if (parseInt(response) !== 1) {
                element.val('');
            }
        });
    },
    triggerQuestionAnswerAjaxCall: function (testExecutionId, data, checkbox, isChecked) {
        $.ajax({
            method: 'POST',
            url: '/ajax/tests/execute/submitQuestionAnswer/' + testExecutionId,
            data: data
        }).success(function (response) {
            if (parseInt(response) !== 1) {
                checkbox.prop('checked', !isChecked);
            }
        });
    },
    initTestCountDown: function () {
        var testCountDown = $('#testCountDown');

        if (testCountDown.length) {
            var executionTimeRemainingInSec = Math.ceil(parseInt(timeRemainingInSec));

            testCountDownTimer = setInterval(function timer() {
                testCountDown.text(test.getCountdownTimeText(executionTimeRemainingInSec));
                if (executionTimeRemainingInSec < 0) {
                    clearInterval(testCountDownTimer);
                    $('#finishExecution').trigger('click');
                } else {
                    --executionTimeRemainingInSec;
                }
            }, 1000);
        }
    },
    getCountdownTimeText: function (timeInSec) {
       var hours = Math.floor(timeInSec / 3600);
       var minutes = Math.floor(timeInSec % 3600 / 60);
       var seconds = Math.floor(timeInSec % 3600 % 60);
       hours = hours < 0 ? '00' : (hours < 10 ? '0' + hours : hours);
       minutes = minutes < 0 ? '00' : (minutes < 10 ? '0' + minutes : minutes);
       seconds = seconds < 0 ? '00' : (seconds < 10 ? '0' + seconds : seconds);

       return hours + ':' + minutes + ':' +  seconds;
    },
    init:function () {
        this.loadTests();
        this.handleQuestionLoadBtn();
        this.loadTestQuestions(window.location.pathname.indexOf('edit') !== -1);
        if (window.location.pathname.indexOf('inviteUsers') !== -1) {
            this.initDateTimePickers();
        }
        this.handleSubmitTestExecution();
        this.loadTestExecutions();
        this.initAnsweredQuestionHandler();
        this.initTestCountDown();
    }
};
test.init();
