<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateZoneRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return $this->authorizeEdit();
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
