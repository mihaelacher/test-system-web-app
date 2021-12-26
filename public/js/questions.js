const singleChoiceType = $('#js-single-choice-type').val(),
    multipleChoiceType = $('#js-multiple-choice-type').val();

let question = {
    loadQuestions: function () {
        let questionsTable = $('#questionsIndexTable');

        if (questionsTable.length) {
            questionsTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: '/ajax/questions/getQuestions',
                    columns: utils.getQuestionDatatableCols(),
                    select: isSelectable
                }
            });
        }
    },

    attachIsQuestionOpenHandler: function () {
        $('#js-question-type').on('change', function () {
            let selection = $(this).val();
            let answerContainer = $('.js-answer-container, #js-clone-answer-container-btn');
            let maxMarkableAnswersInput = $('.js-max-markable-answers');

            if (selection === singleChoiceType || selection === multipleChoiceType) {
                let inputType = 'checkbox';

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
        let form = $('#questionForm');
        form.on('submit', function () {
            let selector = $('.js-correct-answer');

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
        let cloneBtn = $('#js-clone-answer-container-btn');
        let answerContainerSelector = '.js-answer-container';

        if (cloneBtn.length) {
            let answerContainer = $(answerContainerSelector);

            cloneBtn.on('click', function () {
                let container = answerContainer.last();
                let clone = container.clone();

                clone.find('input[type=checkbox]').prop('checked', false);
                clone.find('input').val('');
                clone.insertAfter(container);

                question.attachRemoveAnswerContainerHandler(clone.find('.js-remove-answer-container-btn'));
                question.disableEnableRemoveContainerBtn();
                question.changeMaxMarkableAnswersRule($(answerContainerSelector).length);
            });
        }

    },

    attachRemoveAnswerContainerHandler: function (removeBtn) {
        removeBtn = removeBtn ?? $('.js-remove-answer-container-btn');
        let answerContainerSelector = '.js-answer-container';

        removeBtn.on('click', function () {
            if ($(answerContainerSelector).length > 1) {
                $(this).closest(answerContainerSelector).remove();
            }

            question.disableEnableRemoveContainerBtn();
            question.changeMaxMarkableAnswersRule($(answerContainerSelector).length);
        });
    },

    disableEnableRemoveContainerBtn: function () {
        let disable = false;

        if ($('.js-answer-container').length === 1) {
            disable = true;
        }

        $('.js-remove-answer-container-btn').prop('disabled', disable);
    },

    attachQuestionFormValidator: function () {
        let rules = {
            'text': {required: true},
            'points': {required: true, number: true},
            'value[]': {
                required: {
                    depends: function () {
                        let questionType = $('#js-question-type').val();

                        return questionType === multipleChoiceType
                            || questionType === singleChoiceType
                    }
                }
            },
            'max_markable_answers': {
                required: {
                    depends: function (element) {
                        return $('#js-question-type').val() === multipleChoiceType;
                    }
                },
                min: 2,
                max: $('.js-answer-container').length - 1
            }
        }

        let messages = {
            'text': 'Please, specify question text!',
            'points': 'Please, specify question points!',
            'value[]': 'Please, insert answers to the question!',
            'max_markable_answers': {
                required: 'Please, specify max markable answers!',
                min: 'Should be more than 1 for multiple choice question!',
                max: 'Either add more answers or change max markable answers, please!'
            }
        }

        validator.addFormValidationHandler('questionForm', rules, messages);
    },

    changeMaxMarkableAnswersRule: function (max) {
        $('input[name="max_markable_answers"]').rules('remove', 'max');
        $('input[name="max_markable_answers"]').rules('add', {max: max});
        //    $.validator.element('input[name="max_markable_answers"]');
    },
    init: function () {
        this.loadQuestions();
        this.attachIsQuestionOpenHandler();
        this.attachOnSubmitHandler();
        this.attachCloneAnswerContainerHandler();
        this.attachRemoveAnswerContainerHandler();
        this.attachQuestionFormValidator();
    }
};
question.init();
