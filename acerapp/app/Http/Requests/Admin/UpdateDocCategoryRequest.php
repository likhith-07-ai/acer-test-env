<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $categoryId = $this->route('doc_category')?->id ?? null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($categoryId) {
                    $parentId = $this->input('parent_id');
                    $regulatoryBody = $this->input('regulatory_body');

                    // For sub-categories, get regulatory_body from parent if not provided
                    if ($parentId && !$regulatoryBody) {
                        $parent = \App\Models\DocCategory::find($parentId);
                        if ($parent) {
                            $regulatoryBody = $parent->regulatory_body;
                        }
                    }

                    // Check if category with same name exists under same parent and regulatory body (excluding current category)
                    $query = \App\Models\DocCategory::where('name', $value)
                        ->where('parent_id', $parentId)
                        ->where('regulatory_body', $regulatoryBody)
                        ->where('id', '!=', $categoryId);

                    $exists = $query->exists();

                    if ($exists) {
                        if ($parentId) {
                            $fail('A sub-category with this name already exists under the selected parent category.');
                        } else {
                            $fail('A category with this name already exists for the selected regulatory body.');
                        }
                    }
                },
            ],
            'regulatory_body' => [
                function ($attribute, $value, $fail) {
                    $parentId = $this->input('parent_id');
                    // Regulatory body is required only for main categories (no parent_id)
                    if (!$parentId && !$value) {
                        $fail('The regulatory body field is required for main categories.');
                    }
                    // If provided, must be SEBI, RBI, or OTHER
                    if ($value && !in_array($value, ['SEBI', 'RBI', 'OTHER'])) {
                        $fail('The regulatory body must be SEBI, RBI, or OTHER.');
                    }
                },
            ],
            'short_description' => 'nullable|string|max:500',
            'parent_id' => [
                'nullable',
                'exists:doc_categories,id',
                Rule::notIn([$categoryId]),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'regulatory_body.required' => 'The regulatory body field is required.',
            'regulatory_body.in' => 'The regulatory body must be SEBI, RBI, or OTHER.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoryId = $this->route('docCategory')->id ?? null;
            $parentId = $this->input('parent_id');

            if ($parentId && $parentId == $categoryId) {
                $validator->errors()->add('parent_id', __('validation.custom.parent_id.not_in'));
            }
        });
    }
}
