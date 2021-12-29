<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainFormRequest;

class TestValidateRequest extends MainFormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'max_duration' => 'required|numeric',
            'selected_question_ids' => 'min:1'
        ];
    }

    /** @return string[] */
    public function messages(): array
    {
        return [
            'selected_question_ids.min' => 'Please, choose at least one question for the test.'
        ];
    }

    /** @return void */
    public function prepareForValidation(): void
    {
        $input = $this->all();
        $input['selected_question_ids'] = empty($this->selected_question_ids)
            ? []
            : explode(',', $this->selected_question_ids);

        $this->replace($input);
    }
}
