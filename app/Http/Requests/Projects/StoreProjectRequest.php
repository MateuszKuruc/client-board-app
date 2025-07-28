<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreProjectRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'client_id' => ['required', 'numeric', Rule::exists('clients', 'id')],
            'service_id' => ['required', 'numeric', Rule::exists('services', 'id')],
            'active' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'type' => ['required', Rule::in(['Standard', 'Subskrypcja'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Podaj nazwę projektu',
            'name.max' => 'Nazwa projektu nie może przekraczać 255 znaków',
            'name.min' => 'Nazwa projektu musi mieć minimum 3 znaki',

            'client_id.required' => 'Przypisz projekt do klienta',
            'client_id.numeric' => 'Wybierz klienta',
            'client_id.exists' => 'Wybrany klient nie istnieje',

            'service_id.required' => 'Wybierz rodzaj usługi',
            'service_id.numeric' => 'ID usługi musi być liczbą',
            'service_id.exists' => 'Wybrana usługa nie istnieje',

            'active.required' => 'Wybierz status projektu',

            'price.required' => 'Wprowadź cenę projektu',
            'price.numeric' => 'Cena musi być liczbą',
            'price.min' => 'Cena musi wynosić co najmniej 1 zł',
            'price.max' => 'Cena nie może przekraczać 999 999,99 zł',

            'type.required' => 'Wybierz rodzaj projektu',
            'type.in' => 'Wybrany typ projektu jest nieprawidłowy',

            'start_date.date' => 'Data rozpoczęcia musi być poprawną datą',
            'start_date' => 'Wprowadź poprawną datę startu',
            'end_date.date' => 'Data zakończenia musi być poprawną datą',
            'end_date.after' => 'Data zakończenia musi być późniejsza niż data startu'
        ];
    }
}
