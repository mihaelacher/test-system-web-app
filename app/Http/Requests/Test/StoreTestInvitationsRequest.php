<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\Test;

class StoreTestInvitationsRequest extends MainFormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Test::class);
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'active_from' => 'required|date_format:d.m.Y H:i|before:active_to',
            'active_to' => 'required|date_format:d.m.Y H:i|after:active_from',
            'selected_user_ids' => 'min:1'
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'selected_user_ids.min' => 'Please, choose at least one user.'
        ];
    }

    /** @return void */
    public function prepareForValidation(): void
    {
        $input = $this->all();
        $input['selected_user_ids'] = empty($this->selected_user_ids)
            ? []
            : explode(',', $this->selected_user_ids);

        $this->replace($input);
    }
}
