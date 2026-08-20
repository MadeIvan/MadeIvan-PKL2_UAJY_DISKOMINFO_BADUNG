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
                'exists:tutorial_nodes,id',
            ],
            'new_title' => [
                'nullable',
                'string',
                'max:200',
            ],
        ];
    }
}
