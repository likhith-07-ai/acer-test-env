<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category')->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$categoryId]),
            ],
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
            $categoryId = $this->route('category')->id ?? null;
            $parentId = $this->input('parent_id');

            if ($parentId && $parentId == $categoryId) {
                $validator->errors()->add('parent_id', __('validation.custom.parent_id.not_in'));
            }
        });
    }
}
