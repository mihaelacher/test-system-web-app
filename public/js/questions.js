const singleChoiceType = $('#js-single-choice-type').val(),
    multipleChoiceType = $('#js-multiple-choice-type').val();

let question = {
    loadQuestions: function () {
        let questionsTable = $('#questionsIndexTable');

        if (questionsTable.length) {
            questionsTable.DataTable({
                ...utils.getCommonDatatableOptions(), ...{
                    ajax: '/ajax/questions/getQuestions',
                    columns: utils.getQuestionDatatableCols()
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

    questionFormSubmitHandler: function (form) {
        let selector = $('.js-correct-answer');

        if (selector.length) {
            $.each(selector, function (i, el) {
                $('<input />').attr('type', 'hidden')
                    .attr('name', 'is_correct[]')
                    .attr('value', $(el).is(':checked') ? 1 : 0)
                    .appendTo(form);
            });
        }

        form.submit();
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
                question.updateMaxmarkableAnswersMaxRuleValidation($(answerContainerSelector).length - 1);
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
            question.updateMaxmarkableAnswersMaxRuleValidation($(answerContainerSelector).length - 1);
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
                        return question.isQuestionMultipleChoice();
                    }
                },
                min: {
                    param: 2,
                    depends: function (element) {
                        return question.isQuestionMultipleChoice();
                    }
                },
                max: question.getMaxMarkableMaxRuleObject()
            },
            'correct_answer[]': {correctAnswersValid: true}
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

        validator.addFormValidationHandler('questionForm', rules, messages, null,
            null, question.questionFormSubmitHandler);
    },

    addCustomCorrectAnswersValidator: function () {
        $.validator.addMethod('correctAnswersValid', function () {
            let correctAnswersCount = $('.js-correct-answer:checked').length;

            if (question.isQuestionSingleChoice()) {
                return correctAnswersCount === 1;
            } else if (question.isQuestionMultipleChoice()){
                return correctAnswersCount === parseInt($('input[name="max_markable_answers"]').val());
            }
            return true;
        }, 'Please, indicate correct answers!');
    },

    updateMaxmarkableAnswersMaxRuleValidation: function (value) {
        let selector = $('input[name="max_markable_answers"]');

        selector.rules('remove', 'max');
        selector.rules('add', {max: question.getMaxMarkableMaxRuleObject(value)});
    },

    getMaxMarkableMaxRuleObject: function (max) {
        return {
            param: max ?? ($('.js-answer-container').length - 1),
            depends: function (element) {
                return question.isQuestionMultipleChoice();
            }
        }
    },

    isQuestionMultipleChoice: function () {
        return $('#js-question-type').val() === multipleChoiceType;
    },

    isQuestionSingleChoice: function () {
        return $('#js-question-type').val() === singleChoiceType;
    },

    init: function () {
        this.loadQuestions();

        this.attachIsQuestionOpenHandler();
        this.attachCloneAnswerContainerHandler();
        this.attachRemoveAnswerContainerHandler();

        this.addCustomCorrectAnswersValidator();
        this.attachQuestionFormValidator();
    }
};
question.init();
