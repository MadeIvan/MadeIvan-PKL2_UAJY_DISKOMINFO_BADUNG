<?php

namespace App\Http\Requests\Admin\ApplicationVersion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $application = $this->route('application');

        return [
            'version_number' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'application_versions',
                    'version_number'
                )->where(
                    fn ($query) => $query->where(
                        'application_id',
                        $application->id
                    )
                ),
            ],

            'release_date' => [
                'nullable',
                'date',
            ],

            'release_notes' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'beta',
                    'stable',
                    'deprecated',
                ]),
            ],

            'is_current' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}