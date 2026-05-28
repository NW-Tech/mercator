<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateBackupRequest extends BaseFormRequest
{
    protected array $htmlFields = [];

    public function authorize(): bool
    {
        abort_if(Gate::denies('backup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        $backupId = $this->route('backup')?->id;

        return [
            'name'             => ['required', 'string', 'max:255', Rule::unique('backups', 'name')->ignore($backupId)->whereNull('deleted_at')],
            'type'             => ['nullable', 'string', 'max:100'],
            'description'      => ['nullable', 'string'],
            'backup_frequency' => ['nullable', 'integer', 'min:1', 'max:4'],
            'backup_cycle'     => ['nullable', 'integer', 'min:1', 'max:6'],
            'backup_retention' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
