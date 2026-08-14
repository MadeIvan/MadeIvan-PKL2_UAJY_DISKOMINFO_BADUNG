<?php

namespace App\Http\Requests\Admin\Application;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $application = $this->route('application');

        return [
            'name' => [
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
                Rule::unique('applications', 'slug')
                    ->ignore($application?->id),
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'category_id' => [
                'sometimes',
                'nullable',
                'exists:categories,id',
            ],

            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'archived',
                ]),
            ],

            'is_public' => [
                'sometimes',
                'boolean',
            ],

            'logo' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cover' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'remove_logo' => [
                'sometimes',
                'boolean',
            ],

            'remove_cover' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}