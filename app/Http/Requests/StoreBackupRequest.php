<?php

namespace App\Http\Requests;

use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreBackupRequest extends BaseFormRequest
{
    protected array $htmlFields = [];

    public function authorize(): bool
    {
        abort_if(Gate::denies('backup_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255', 'unique:backups,name'],
            'type'             => ['nullable', 'string', 'max:100'],
            'description'      => ['nullable', 'string'],
            'backup_frequency' => ['nullable', 'integer', 'min:1', 'max:4'],
            'backup_cycle'     => ['nullable', 'integer', 'min:1', 'max:6'],
            'backup_retention' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
