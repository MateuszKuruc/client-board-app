<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StorePaymentRequest extends FormRequest
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
            'project_id' => ['required', 'exists:projects,id'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'status' => ['required', Rule::in(['paid', 'pending', 'cancelled'])],
            'payment_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Przypisz płatność do projektu',
            'amount.required' => 'Podaj kwotę płatności',
            'amount.numeric' => 'Kwota musi być liczbą',
            'amount.min' => 'Kwota musi być dodatnia',
            'amount.max' => 'Kwota nie może przekraczać 999 999,99',
            'status' => 'Wybierz status płatności',
            'payment_date.date' => 'Data płatności jest nieprawidłowa'
        ];
    }
}
