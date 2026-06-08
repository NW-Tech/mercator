<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Autorise la modification si l'utilisateur a la permission _edit
     * OU s'il est cartographe de l'objet passé en route model binding.
     */
    protected function authorizeEdit(): bool
    {
        foreach ($this->route()->parameters() as $param) {
            if ($param instanceof \Illuminate\Database\Eloquent\Model) {
                return Gate::allows('edit-object', $param);
            }
        }

        return false;
    }

    /**
     * Champs contenant du HTML riche (CKEditor)
     * À surcharger dans les FormRequest enfants
     */
    protected array $htmlFields = [];

    protected function prepareForValidation(): void
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            if (!is_string($value)) {
                $sanitized[$key] = $value;
                continue;
            }

            if (in_array($key, $this->htmlFields)) {
                // Champ HTML riche : sanitiser en conservant les balises sûres
                $sanitized[$key] = clean($value); // helper de mews/purifier
            } else {
                // Champ texte : supprimer toutes les balises
                $sanitized[$key] = strip_tags($value);
            }
        }

        $this->merge($sanitized);
    }
}
