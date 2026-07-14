<?php

namespace App\Http\Requests;

use App\Models\DocCategory;
use Illuminate\Foundation\Http\FormRequest;

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
            'regulator' => ['required', 'in:SEBI,RBI,OTHER'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:doc_categories,id'],
            'sub_category_id' => [
                'nullable',
                'exists:doc_categories,id',
                function ($attribute, $value, $fail) {
                    if ($value && $this->category_id) {
                        $subCategory = DocCategory::find($value);
                        if ($subCategory && $subCategory->parent_id != $this->category_id) {
                            $fail(__('validation.sub_category_belongs_to_category'));
                        }
                    }
                },
            ],
            'access_type' => ['required', 'in:public,restricted'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
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
            'regulator.required' => 'Please select a regulator.',
            'regulator.in' => 'The regulator must be SEBI, RBI, or OTHER.',
            'title.required' => 'The document title is required.',
            'title.max' => 'The document title cannot exceed 255 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'sub_category_id.exists' => 'The selected sub-category is invalid.',
            'access_type.required' => 'Please select an access type.',
            'access_type.in' => 'The access type must be either public or restricted.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'The file must be a PDF, DOC, or DOCX file.',
            'file.max' => 'The file size cannot exceed 10MB.',
        ];
    }
}


