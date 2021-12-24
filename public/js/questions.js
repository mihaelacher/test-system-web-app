var question = {
    loadQuestions: function (isSelectable = false) {
        var questionsTable = $('#questionsIndexTable');

        if (questionsTable.length) {
            questionsTable.DataTable({
                ajax: '/ajax/questions/getQuestions',
                columns: question.getQuestionDatatableCols(),
                responsive: true,
                select: isSelectable,
                bFilter: false,
                lengthChange: false,
                ordering: false,
                info: false,
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

    attachIsQuestionOpenHandler: function () {
        $('#js-question-type').on('change', function () {
            var selection = $(this).val();
            var answerContainer = $('.js-answer-container, #js-clone-answer-container-btn');
            var singleChoiceType = $('#js-single-choice-type').val();
            var multipleChoiceType = $('#js-multiple-choice-type').val();
            var maxMarkableAnswersInput = $('.js-max-markable-answers');

            if (selection === singleChoiceType || selection === multipleChoiceType) {
                var inputType = 'checkbox';

                if (selection === singleChoiceType) {
                    inputType = 'radio';
                    maxMarkableAnswersInput.addClass('hidden');
                } else {
                    maxMarkableAnswersInput.removeClass('hidden');
                }

                $('.js-correct-answer').prop('type', inputType);
                answerContainer.removeClass('hidden');
            } else {
                answerContainer.addClass('hidden');
                maxMarkableAnswersInput.addClass('hidden');
            }
        });
    },

    attachOnSubmitHandler: function () {
        var form = $('#questionForm');
        form.on('submit', function () {
            var selector = $('.js-correct-answer');

            if (selector.length) {
                $.each(selector, function (i, el) {
                    $('<input />').attr('type', 'hidden')
                        .attr('name', 'is_correct[]')
                        .attr('value', $(el).is(':checked') ? 1 : 0)
                        .appendTo(form);
                });
            }
        });
    },

    attachCloneAnswerContainerHandler: function () {
        var cloneBtn = $('#js-clone-answer-container-btn');

        if (cloneBtn.length) {
            cloneBtn.on('click', function () {
                var container = $('.js-answer-container').last();
                var clone = container.clone();

                clone.find('input[type=checkbox]').prop('checked', false);
                clone.find('input').val('');
                clone.insertAfter(container);

                question.attachRemoveAnswerContainerHandler(clone.find('.js-remove-answer-container-btn'));
                question.disableEnableRemoveContainerBtn();
            });
        }
    },

    attachRemoveAnswerContainerHandler: function (removeBtn) {
        removeBtn = removeBtn ?? $('.js-remove-answer-container-btn');
        var answerContainerSelector = '.js-answer-container';

        removeBtn.on('click', function () {
            if ($(answerContainerSelector).length > 1) {
                $(this).closest(answerContainerSelector).remove();
            }

            question.disableEnableRemoveContainerBtn();
        });
    },

    disableEnableRemoveContainerBtn: function () {
        var disable = false;

        if ($('.js-answer-container').length === 1) {
            disable = true;
        }

        $('.js-remove-answer-container-btn').prop('disabled', disable);
    },

    attachQuestionFormValidator: function () {
        $('#questionForm .btnSubmitForm').on('click', function () {
           let rules = {
               'text': {required: true},
               'points': {required: true}
           }
           validator.addFormValidationHandler('questionForm', rules);
        });
    },

    init:function () {
       this.loadQuestions();
       this.attachIsQuestionOpenHandler();
       this.attachOnSubmitHandler();
       this.attachCloneAnswerContainerHandler();
       this.attachRemoveAnswerContainerHandler();
       this.attachQuestionFormValidator();
    }
};
question.init();
