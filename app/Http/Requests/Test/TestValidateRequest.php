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
        ];
    }

    /**
     * @param array $input
     * @return TestValidateRequest
     */
    public function replace(array $input): TestValidateRequest
    {
        $input['selected_question_ids'] = explode(',', request()->selected_question_ids ?? '');
        return parent::replace($input);
    }
}
