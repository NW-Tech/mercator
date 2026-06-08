<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Illuminate\Validation\Rule;

class UpdateRouterRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'rules'];

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
                Rule::unique('routers')
                    ->ignore($this->route('router')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'ip_addresses' => [
                'nullable',
                new IPList,
            ],
        ];
    }
}
