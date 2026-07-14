<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DocCategory;

class UpdateDocumentRequest extends FormRequest
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
        return [
            'regulator' => 'required|in:SEBI,RBI,OTHER',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:doc_categories,id',
            'sub_category_id' => [
                'nullable',
                'exists:doc_categories,id',
            ],
            'access_type' => 'required|in:public,restricted',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
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
            $categoryId = $this->input('category_id');
            $subCategoryId = $this->input('sub_category_id');

            // Validate that sub-category belongs to the parent category
            if ($subCategoryId && $categoryId) {
                $subCategory = DocCategory::find($subCategoryId);
                
                if (!$subCategory) {
                    $validator->errors()->add('sub_category_id', __('validation.custom.sub_category_id.exists'));
                    return;
                }

                // Check if sub-category belongs to the selected category
                if ($subCategory->parent_id !== (int)$categoryId) {
                    $validator->errors()->add(
                        'sub_category_id',
                        __('validation.custom.sub_category_id.belongs_to_parent')
                    );
                }

                // Ensure sub-category is actually a sub-category (has a parent)
                if ($subCategory->parent_id === null) {
                    $validator->errors()->add(
                        'sub_category_id',
                        __('validation.custom.sub_category_id.must_be_child')
                    );
                }
            }
        });
    }
}
