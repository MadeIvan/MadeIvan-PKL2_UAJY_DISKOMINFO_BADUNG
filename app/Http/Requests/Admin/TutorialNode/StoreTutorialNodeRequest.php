<?php

namespace App\Http\Requests\Admin\TutorialNode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTutorialNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'application_id' => [
                'required',
                'integer',
                'exists:applications,id',
            ],

            'application_version_id' => [
                'nullable',
                'integer',
                'exists:application_versions,id',
            ],

            'parent_id' => [
                'nullable',
                'integer',
                'exists:tutorial_nodes,id',
            ],

            'title' => [
                'required',
                'string',
                'max:200',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:200',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'node_type' => [
                'required',
                Rule::in([
                    'category',
                    'section',
                    'tutorial',
                    'step',
                ]),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'is_public' => [
                'required',
                'boolean',
            ],
        ];
    }
}