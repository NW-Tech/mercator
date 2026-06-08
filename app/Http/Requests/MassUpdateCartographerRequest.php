<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassUpdateCartographerRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('cartographer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        $updateRules = (new UpdateCartographerRequest())->rules();

        $rules = [
            'items'      => ['required', 'array', 'min:1'],
            'items.*'    => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:cartographers,id'],
        ];

        foreach ($updateRules as $field => $rule) {
            $rules["items.*.$field"] = $rule;
        }

        return $rules;
    }
}
