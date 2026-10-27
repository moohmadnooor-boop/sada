<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'bio' => ['nullable', 'string', 'max:160'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.min' => 'الاسم يجب أن يكون حرفين على الأقل.',
            'name.max' => 'الاسم يجب ألا يتجاوز 50 حرفاً.',
            'name.regex' => 'الاسم يجب أن يحتوي على حروف فقط، بدون أرقام أو رموز.',
            'bio.max' => 'النبذة يجب ألا تتجاوز 160 حرفاً.',
        ];
    }
}
