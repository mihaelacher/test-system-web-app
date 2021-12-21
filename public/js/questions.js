var question = {
    loadQuestions: function (isSelectable = false) {
        var questionsTable = $('#questionsIndexTable');

        if (questionsTable.length) {
            questionsTable.DataTable({
                ajax: '/ajax/questions/getQuestions',
                columns: question.getQuestionDatatableCols(),
                responsive: true,
                select: isSelectable
            });
        }
    },
    getQuestionDatatableCols: function () {
        return [
            {
                data: 'title',
                name: 'title',
                orderable: false,
                searchable: false
            }, {
                data: 'question',
                name: 'question',
                orderable: false,
                searchable: false
            }, {
                data: 'points',
                name: 'points',
                orderable: false,
                searchable: false
            }, {
                data: 'type',
                name: 'type',
                orderable: false,
                searchable: false
            }
        ];
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
    attachOnSubmitHandler: function () {
        $('#questionForm').on('submit', function () {
            question.generateRequestData();
        });
    },
    generateRequestData:function () {
        var selector = $('.isCorrectSelect');

        if (selector.length) {
            $.each(selector, function (i, el) {
                $('<input />').attr('type', 'hidden')
                    .attr('name', 'is_correct[]')
                    .attr('value', $(el).is(':checked') ? 1 : 0)
                    .appendTo($('#questionForm'));
            });
        }
    },
    init:function () {
       this.loadQuestions();
       this.attachIsQuestionOpenEventListener();
       this.attachOnSubmitHandler();
    }
};
question.init();
