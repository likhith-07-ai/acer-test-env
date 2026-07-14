<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublishResearchArticleRequest extends FormRequest
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
            'published_at' => ['nullable', 'date', 'after_or_equal:now'],
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
            'published_at.date' => 'The publish date must be a valid date.',
            'published_at.after_or_equal' => 'The publish date must be today or a future date.',
        ];
    }
}



