<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSettingsRequest extends FormRequest
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
            'currency' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'date_format' => ['nullable', 'string', 'in:Y-m-d,d/m/Y,m/d/Y,d-m-Y'],
            'language' => ['nullable', 'string', 'in:en,id'],
            'notifications' => ['nullable', 'array'],
            'notifications.email' => ['nullable', 'boolean'],
            'notifications.push' => ['nullable', 'boolean'],
            'notifications.reminders' => ['nullable', 'boolean'],
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
            'dashboard_layout' => ['nullable', 'string', 'in:grid,list,compact'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'currency.max' => 'Currency code cannot exceed 10 characters.',
            'timezone.max' => 'Timezone cannot exceed 50 characters.',
            'date_format.in' => 'Please select a valid date format.',
            'language.in' => 'Please select a valid language.',
            'theme.in' => 'Please select a valid theme option.',
            'dashboard_layout.in' => 'Please select a valid dashboard layout.',
        ];
    }
}
