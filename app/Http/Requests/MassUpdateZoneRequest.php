<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Zone;
use Symfony\Component\HttpFoundation\Response;

class MassUpdateZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('zone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        $updateRules = (new UpdateZoneRequest())->rules();
        $table       = (new Zone())->getTable();

        $rules = [
            'items'      => ['required', 'array', 'min:1'],
            'items.*'    => ['required', 'array'],
            'items.*.id' => ['required', 'integer', "exists:{$table},id"],
        ];

        foreach ($updateRules as $field => $rule) {
            $rules["items.*.$field"] = $rule;
        }

        return $rules;
    }
}
