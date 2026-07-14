<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResearchArticleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:research_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:research_tags,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_restricted' => ['boolean'],
            'status' => ['required', 'in:draft,submitted'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
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
            'title.required' => 'The article title is required.',
            'title.max' => 'The article title cannot exceed 255 characters.',
            'excerpt.max' => 'The excerpt cannot exceed 500 characters.',
            'content.required' => 'The article content is required.',
            'category_id.exists' => 'The selected category is invalid.',
            'tags.array' => 'Tags must be an array.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
            'featured_image.image' => 'The featured image must be an image file.',
            'featured_image.mimes' => 'The featured image must be a JPEG, PNG, JPG, GIF, or WEBP file.',
            'featured_image.max' => 'The featured image size cannot exceed 5MB.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be either draft or submitted.',
            'meta_description.max' => 'The meta description cannot exceed 500 characters.',
            'meta_keywords.max' => 'The meta keywords cannot exceed 255 characters.',
        ];
    }
}



