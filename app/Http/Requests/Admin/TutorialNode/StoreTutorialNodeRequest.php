<?php

namespace App\Http\Requests\Admin\TutorialNode;

use App\Models\TutorialNode;
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
                'required',
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
                Rule::in(
                    TutorialNode::TYPES
                ),
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

    public function messages(): array
    {
        return [
            'application_id.required' =>
                'Aplikasi wajib dipilih.',

            'application_id.exists' =>
                'Aplikasi yang dipilih tidak tersedia.',

            'application_version_id.required' =>
                'Versi aplikasi wajib dipilih.',

            'application_version_id.exists' =>
                'Versi aplikasi yang dipilih tidak tersedia.',

            'parent_id.exists' =>
                'Parent materi tidak ditemukan.',

            'title.required' =>
                'Judul materi wajib diisi.',

            'title.max' =>
                'Judul materi maksimal 200 karakter.',

            'slug.max' =>
                'Slug maksimal 200 karakter.',

            'node_type.required' =>
                'Jenis materi wajib dipilih.',

            'node_type.in' =>
                'Jenis materi hanya boleh Kategori, Bagian, atau Materi.',

            'sort_order.integer' =>
                'Urutan materi harus berupa angka.',

            'sort_order.min' =>
                'Urutan materi tidak boleh kurang dari 0.',

            'status.required' =>
                'Status materi wajib dipilih.',

            'status.in' =>
                'Status materi tidak valid.',

            'is_public.required' =>
                'Pengaturan publik wajib dikirim.',

            'is_public.boolean' =>
                'Pengaturan publik harus berupa nilai benar atau salah.',
        ];
    }
}