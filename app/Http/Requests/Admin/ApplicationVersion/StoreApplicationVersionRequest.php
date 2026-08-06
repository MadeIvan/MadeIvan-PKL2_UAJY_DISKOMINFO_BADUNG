<?php

namespace App\Http\Requests\Admin\ApplicationVersion;

use App\Models\ApplicationVersion;
use App\Models\TutorialNode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreApplicationVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $application = $this->route('application');

        return [
            'version_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'application_versions',
                    'version_number'
                )->where(
                    fn ($query) => $query->where(
                        'application_id',
                        $application->id
                    )
                ),
            ],

            'release_date' => [
                'nullable',
                'date',
            ],

            'release_notes' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'beta',
                    'stable',
                    'deprecated',
                ]),
            ],

            'is_current' => [
                'nullable',
                'boolean',
            ],

            'copy_materials' => [
                'nullable',
                'boolean',
            ],

            'source_version_id' => [
                'nullable',
                Rule::requiredIf(
                    fn (): bool =>
                        $this->boolean('copy_materials')
                ),
                'integer',
                'exists:application_versions,id',
            ],

            'selected_node_ids' => [
                'nullable',
                Rule::requiredIf(
                    fn (): bool =>
                        $this->boolean('copy_materials')
                ),
                'array',
                'min:1',
            ],

            'selected_node_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:tutorial_nodes,id',
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(
            function (Validator $validator): void {
                if (!$this->boolean('copy_materials')) {
                    return;
                }

                $application = $this->route('application');

                $sourceVersionId = (int) $this->input(
                    'source_version_id'
                );

                $sourceVersion =
                    ApplicationVersion::query()
                        ->whereKey($sourceVersionId)
                        ->where(
                            'application_id',
                            $application->id
                        )
                        ->first();

                if (!$sourceVersion) {
                    $validator->errors()->add(
                        'source_version_id',
                        'Versi sumber harus berasal dari aplikasi yang sama.'
                    );

                    return;
                }

                $selectedNodeIds = collect(
                    $this->input(
                        'selected_node_ids',
                        []
                    )
                )
                    ->map(
                        fn ($id): int => (int) $id
                    )
                    ->unique()
                    ->values();

                if ($selectedNodeIds->isEmpty()) {
                    return;
                }

                $validNodeCount =
                    TutorialNode::query()
                        ->whereIn(
                            'id',
                            $selectedNodeIds
                        )
                        ->where(
                            'application_id',
                            $application->id
                        )
                        ->where(
                            'application_version_id',
                            $sourceVersion->id
                        )
                        ->count();

                if (
                    $validNodeCount !==
                    $selectedNodeIds->count()
                ) {
                    $validator->errors()->add(
                        'selected_node_ids',
                        'Semua materi yang dipilih harus berasal dari versi sumber yang dipilih.'
                    );
                }
            }
        );
    }

    public function messages(): array
    {
        return [
            'version_number.required' =>
                'Nomor versi wajib diisi.',

            'version_number.unique' =>
                'Nomor versi sudah digunakan pada aplikasi ini.',

            'status.required' =>
                'Status versi wajib dipilih.',

            'status.in' =>
                'Status versi tidak valid.',

            'source_version_id.required_if' =>
                'Versi sumber wajib dipilih ketika menyalin materi.',

            'source_version_id.exists' =>
                'Versi sumber tidak ditemukan.',

            'selected_node_ids.required_if' =>
                'Pilih minimal satu materi yang akan disalin.',

            'selected_node_ids.min' =>
                'Pilih minimal satu materi yang akan disalin.',

            'selected_node_ids.*.exists' =>
                'Salah satu materi yang dipilih tidak ditemukan.',
        ];
    }
}
