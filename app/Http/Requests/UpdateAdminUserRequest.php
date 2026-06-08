<?php

namespace App\Http\Requests;


class UpdateAdminUserRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize(): bool
    {
        return $this->authorizeEdit();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'min:3',
                'max:32',
                'required',
            ],
            'firstname' => [
                'max:64',
            ],
            'lastname' => [
                'max:64',
            ],
        ];
    }
}
