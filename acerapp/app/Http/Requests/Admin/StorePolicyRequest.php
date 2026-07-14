<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
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
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'tagline' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'is_restricted' => 'boolean',
            'file' => 'nullable|file|mimes:pdf|max:10240',
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
            'title.required' => 'The policy title is required.',
            'title.max' => 'The policy title cannot exceed 255 characters.',
            'tagline.max' => 'The tagline cannot exceed 500 characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be either draft or published.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'The file must be a PDF file.',
            'file.max' => 'The file size cannot exceed 10MB.',
        ];
    }
}
