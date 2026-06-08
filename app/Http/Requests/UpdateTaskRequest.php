<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateTaskRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        return $this->authorizeEdit();
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('tasks')
                    ->ignore($this->route('task')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
