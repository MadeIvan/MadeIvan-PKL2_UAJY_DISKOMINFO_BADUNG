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

            'category_id' => [
                'nullable',
                'exists:categories,id',
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

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cover' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'logo.image' => 'Logo must be an image.',
            'logo.mimes' => 'Logo must use JPG, JPEG, PNG, or WebP format.',
            'logo.max' => 'Logo size must not exceed 2 MB.',

            'cover.image' => 'Cover must be an image.',
            'cover.mimes' => 'Cover must use JPG, JPEG, PNG, or WebP format.',
            'cover.max' => 'Cover size must not exceed 4 MB.',
        ];
    }
}