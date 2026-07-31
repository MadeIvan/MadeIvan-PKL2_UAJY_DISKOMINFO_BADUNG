<?php

namespace App\Http\Requests\Admin\TutorialContentBlock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTutorialContentBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'block_type' => [
                'sometimes',
                'required',
                Rule::in([
                    'text',
                    'image',
                    'youtube',
                    'pdf',
                ]),
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'external_url' => [
                'nullable',
                'url',
            ],

            'file' => [
                'nullable',
                'file',
                'max:20480',
            ],

            'caption' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alt_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'metadata' => [
                'nullable',
                'array',
            ],

            'title' => [
            'nullable',
            'string',
            'max:255',
        ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $blockType = $this->input(
                'block_type',
                $this->route('tutorialContentBlock')?->block_type
            );

            $file = $this->file('file');

            if ($blockType === 'text' && !$this->filled('content')) {
                $validator->errors()->add(
                    'content',
                    'Isi teks wajib diisi.'
                );
            }

            if ($blockType === 'youtube' && !$this->filled('external_url')) {
                $validator->errors()->add(
                    'external_url',
                    'Tautan YouTube wajib diisi.'
                );
            }

            if (!$file) {
                return;
            }

            if (
                $blockType === 'image' &&
                !in_array(
                    $file->getMimeType(),
                    [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ],
                    true
                )
            ) {
                $validator->errors()->add(
                    'file',
                    'File gambar harus berupa JPG, PNG, atau WEBP.'
                );
            }

            if (
                $blockType === 'pdf' &&
                $file->getMimeType() !== 'application/pdf'
            ) {
                $validator->errors()->add(
                    'file',
                    'Dokumen harus berupa file PDF.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'block_type.in' => 'Jenis blok tidak valid.',
            'external_url.url' => 'Tautan YouTube tidak valid.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
            'title.required_if' => 'Judul video YouTube wajib diisi.',
        ];
    }
}