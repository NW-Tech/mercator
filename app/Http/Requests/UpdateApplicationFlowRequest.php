<?php

namespace App\Http\Requests;


class UpdateApplicationFlowRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        return $this->authorizeEdit();
    }

    public function rules() : array
    {
        return [
            'name' => ['min:3', 'max:64', 'required'],
            /*
            'application_source_id' =>
                ['required_without_all:service_source_id,module_source_id,database_source_id'],
            'service_source_id' =>
                ['required_without_all:application_source_id,module_source_id,database_source_id'],
            'module_source_id' =>
                ['required_without_all:application_source_id,service_source_id,database_source_id'],
            'database_source_id' =>
                ['required_without_all:application_source_id,service_source_id,module_source_id'],
            */

        ];
    }
}
