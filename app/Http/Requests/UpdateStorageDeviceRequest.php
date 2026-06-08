<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateStorageDeviceRequest extends BaseFormRequest
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
                Rule::unique('storage_devices')
                    ->ignore($this->route('storage_device')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
