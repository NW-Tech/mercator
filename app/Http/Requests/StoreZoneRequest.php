<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('zone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:zones,name',
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
