<?php

namespace App\Http\Requests\Admin\TutorialContentBlock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTutorialContentBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'block_type' => [
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
                'required_if:block_type,text',
            ],

            'external_url' => [
                'nullable',
                'url',
                'required_if:block_type,youtube',
            ],

            'file' => [
                'nullable',
                'file',
                'required_if:block_type,image,pdf',
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
            $blockType = $this->input('block_type');
            $file = $this->file('file');

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

            if (
                !in_array($blockType, ['image', 'pdf'], true) &&
                $file
            ) {
                $validator->errors()->add(
                    'file',
                    'Jenis blok ini tidak menerima file.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'block_type.required' => 'Jenis blok wajib dipilih.',
            'block_type.in' => 'Jenis blok tidak valid.',
            'content.required_if' => 'Isi teks wajib diisi.',
            'external_url.required_if' => 'Tautan YouTube wajib diisi.',
            'external_url.url' => 'Tautan YouTube tidak valid.',
            'file.required_if' => 'File wajib dipilih.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
            'title.required_if' => 'Judul video YouTube wajib diisi.',
        ];
    }
}