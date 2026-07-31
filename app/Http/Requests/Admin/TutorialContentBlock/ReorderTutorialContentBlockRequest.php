<?php

namespace App\Http\Requests\Admin\TutorialContentBlock;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTutorialContentBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blocks' => [
                'required',
                'array',
                'min:1',
            ],

            'blocks.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:tutorial_content_blocks,id',
            ],

            'blocks.*.sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'blocks.required' => 'Daftar urutan blok wajib dikirim.',
            'blocks.array' => 'Format urutan blok tidak valid.',
            'blocks.*.id.distinct' => 'Terdapat blok yang dikirim lebih dari satu kali.',
            'blocks.*.id.exists' => 'Salah satu blok tidak ditemukan.',
        ];
    }
}