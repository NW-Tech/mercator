<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreDomainRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('domain_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('domains')->whereNull('deleted_at'),
            ],
            'domain_ctrl_cnt' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
            'user_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
            'machine_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
        ];
    }
}
