var question = {
    loadQuestions: function () {
        var questionsTable = $('#questionsIndexTable');
        if (questionsTable.length) {
            questionsTable.DataTable({
                ajax: '/ajax/questions/getQuestions',
                columns: [{
                    data: 'title',
                    name: 'title'
                }, {
                    data: 'question',
                    name: 'question'
                }, {
                    data: 'points',
                    name: 'points'
                }, {
                    data: 'type',
                    name: 'type'
                }],
                responsive: true
            });
        }
    },
    attachIsQuestionOpenEventListener: function () {
        $('#isQuestionOpen').on('change', function () {
            var answersForm = $('#answersForm');
            if (parseInt($(this).val()) !== 1) {
                answersForm.removeClass('hidden');
            } else {
                answersForm.addClass('hidden');
            }
        });
    },
    generateRequestData:function () {
        var selector = $('.isCorrectSelect');

        if (selector.length) {
            $.each(selector, function (i, el) {
               $('<input />').attr('type', 'hidden')
                   .attr('name', 'is_correct[]')
                   .attr('value', $(el).is(':checked') ? 1 : 0)
                   .appendTo($('#questionCreateForm'));
            });
        }
    },
    attachOnSubmitHandler: function () {
        $('#questionCreateForm').on('submit', function () {
            question.generateRequestData();
        });
    },
    init:function () {
       this.loadQuestions();
       this.attachIsQuestionOpenEventListener();
       this.attachOnSubmitHandler();
    }
};
question.init();
