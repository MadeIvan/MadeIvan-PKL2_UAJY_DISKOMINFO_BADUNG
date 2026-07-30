<?php

namespace App\Http\Requests\Admin\TutorialNode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTutorialNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'application_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:applications,id',
            ],

            'application_version_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:application_versions,id',
            ],

            'parent_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:tutorial_nodes,id',
            ],

            'title' => [
                'sometimes',
                'required',
                'string',
                'max:200',
            ],

            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:200',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'node_type' => [
                'sometimes',
                'required',
                Rule::in([
                    'category',
                    'section',
                    'tutorial',
                    'step',
                ]),
            ],

            'sort_order' => [
                'sometimes',
                'integer',
                'min:0',
            ],

            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'is_public' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}