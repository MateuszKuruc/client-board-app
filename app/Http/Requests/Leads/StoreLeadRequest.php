<?php

namespace App\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', Rule::unique('leads', 'email')],
            'phone' => ['nullable', 'digits_between:9,11', Rule::unique('leads', 'phone')],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Podaj adres e-mail',
            'email.email' => 'Podaj poprawny adres e-mail',
            'email.unique' => 'Ten adres email już istnieje',
            'phone.unique' => 'Ten numer telefonu już istnieje',
            'phone.digits_between' => 'Telefon musi mieć między 9 a 11 cyfr',
        ];
    }
}
