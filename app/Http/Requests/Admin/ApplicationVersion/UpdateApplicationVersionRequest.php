<?php

namespace App\Http\Requests\Admin\ApplicationVersion;

use App\Models\ApplicationVersion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $applicationVersion = $this->route(
            'applicationVersion'
        );

        return [
            'version_number' => [
                'sometimes',
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'application_versions',
                    'version_number'
                )
                    ->where(
                        fn ($query) => $query->where(
                            'application_id',
                            $applicationVersion->application_id
                        )
                    )
                    ->ignore($applicationVersion->id),
            ],

            'release_date' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'release_notes' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'status' => [
                'sometimes',
                'required',
                Rule::in(ApplicationVersion::STATUSES),
            ],

            'is_current' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}