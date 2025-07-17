<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateClientRequest extends FormRequest
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
        $clientId = $this->route('client')->id;

        return [
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients', 'email')->ignore($clientId)],
            'phone' => ['nullable', 'digits_between:9,11', Rule::unique('clients', 'phone')->ignore($clientId)],
            'nip' => ['nullable', 'min:8', 'max:12'],
            'source' => [
                'required',
                Rule::in(['Strona internetowa', 'Social media', 'Polecenie', 'Ads', 'Grupki', 'Useme', 'Inne'])
            ],
            'location' => ['required', Rule::in(['local', 'remote', 'international'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Podaj nazwę klienta',
            'name.min' => 'Nazwa klienta musi mieć minimum 3 znaki',
            'name.max' => 'Nazwa klienta może mieć maksymalnie 255 znaków',

            'email.required' => 'Podaj adres e‑mail',
            'email.email' => 'Podaj poprawny adres e‑mail',
            'email.unique' => 'Ten adres e‑mail jest już zajęty',

            'phone.digits_between' => 'Telefon musi mieć od 9 do 11 cyfr',
            'phone.unique' => 'Ten numer telefonu już istnieje',

            'nip' => 'NIP powinien składać się z 10 cyfr',

            'source.required' => 'Wybierz źródło klienta',
            'source.in' => 'Wybrane źródło jest nieprawidłowe',

            'location.required' => 'Wybierz lokalizację klienta',
            'location.in' => 'Wybrana lokalizacja jest nieprawidłowa',
        ];
    }
}
