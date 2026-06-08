<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateZoneAdminRequest extends BaseFormRequest
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
                Rule::unique('zone_admins')
                    ->ignore($this->route('zone_admin')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
