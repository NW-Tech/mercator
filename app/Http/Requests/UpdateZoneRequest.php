<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('zone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255', Rule::unique('zones', 'name')->ignore($this->route('zone')->id ?? $this->id)->whereNull('deleted_at')],
            'type'        => 'nullable|string|max:255',
            'attributes'  => 'nullable',
            'description' => 'nullable|string',
            'parentZones' => 'nullable|array',
            'parentZones.*' => 'exists:zones,id',
            'childZones'  => 'nullable|array',
            'childZones.*' => 'exists:zones,id',
            'buildings'   => 'nullable|array',
            'buildings.*' => 'exists:buildings,id',
            'adminUsers'  => 'nullable|array',
            'adminUsers.*' => 'exists:admin_users,id',
        ];
    }
}
