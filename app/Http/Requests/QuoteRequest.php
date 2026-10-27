<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class QuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:3', 'max:155'],
            'author' => ['nullable', 'string', 'max:80'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'book_id' => ['nullable', 'integer', 'exists:books,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'اكتب نص الاقتباس.',
            'content.min' => 'الاقتباس يجب أن يكون 3 أحرف على الأقل.',
            'content.max' => 'الاقتباس يجب ألا يتجاوز 155 حرفاً.',
            'author.max' => 'اسم القائل يجب ألا يتجاوز 80 حرفاً.',
            'category_id.exists' => 'التصنيف المختار غير موجود.',
            'book_id.exists' => 'الكتاب المختار غير موجود في المكتبة.',
        ];
    }
}
