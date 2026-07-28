<?php

namespace App\Http\Requests\Admin\Application;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:200',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:200',
                'unique:applications,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'category_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'archived',
                ]),
            ],

            'is_public' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}