<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateManRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('mans')
                    ->ignore($this->route('man')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'lans.*' => [
                'integer',
            ],
            'lans' => [
                'array',
            ],
        ];
    }
}
