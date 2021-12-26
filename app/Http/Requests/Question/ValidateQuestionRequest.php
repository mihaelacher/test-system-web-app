<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\MainFormRequest;
use App\Models\Question\QuestionType;

class ValidateQuestionRequest extends MainFormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        $multipleChoiceQuestionId = QuestionType::MULTIPLE_CHOICE;
        $singleChoiceQuestionId = QuestionType::SINGLE_CHOICE;

        $rules = [
            'text' => 'required',
            'points' => 'required|numeric',
            'type' => 'required|in:' . implode(',', array_merge(QuestionType::OPEN_QUESTIONS,
                    QuestionType::CLOSED_QUESTIONS)),
            'max_markable_answers' => 'required_if:type,' . $multipleChoiceQuestionId,
            'value.*' => 'required_if:type,' . $multipleChoiceQuestionId . ',' . $singleChoiceQuestionId
        ];

        $questionTypeId = (int) request()->type;
        if ($questionTypeId === $multipleChoiceQuestionId || $questionTypeId === $singleChoiceQuestionId) {
            $rules['correct_answer'] = 'required|min:' . ($multipleChoiceQuestionId ? request()->max_markable_answers : 1);
        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'type.in' => 'Invalid question type!',
            'value.*.required_if' => 'Please, specify answers for closed questions.',
            'max_markable_answers.required_if' => 'Please, specify max markable answers for multiple choice questions.',
            'correct_answer.min' => 'Please, specify required correct answers.'
        ];
    }
}
