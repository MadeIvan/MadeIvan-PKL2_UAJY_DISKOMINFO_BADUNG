<?php

namespace App\Http\Requests\Admin\TutorialNode;

use Illuminate\Foundation\Http\FormRequest;

class CopyTutorialNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_node_id' => [
                'required',
                'integer',
                'exists:tutorial_nodes,id',
            ],
            'destination_version_id' => [
                'required',
                'integer',
                'exists:application_versions,id',
            ],
            'destination_parent_id' => [
                'nullable',
                'integer',
                function (string $attribute, mixed $value, callable $fail): void {
                    if ($value !== null && $this->has('destination_version_id')) {
                        $version = \App\Models\ApplicationVersion::query()->find($this->input('destination_version_id'));
                        if (!$version) {
                            $fail('Versi tujuan tidak valid.');
                            return;
                        }
                        $exists = \App\Models\TutorialNode::query()
                            ->where('id', $value)
                            ->where('application_id', $version->application_id)
                            ->exists();
                        if (!$exists) {
                            $fail('Materi induk tidak valid untuk versi tujuan.');
                        }
                    }
                },
            ],
            'new_title' => [
                'nullable',
                'string',
                'max:200',
            ],
            'include_children' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
