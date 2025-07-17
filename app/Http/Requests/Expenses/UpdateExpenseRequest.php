<?php

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateExpenseRequest extends FormRequest
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
            'name' => ['required', 'max:255', 'string', 'min:3'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'is_paid' => ['required', 'boolean'],
            'type' => ['required', Rule::in(['Miesięczna', 'Roczna', 'Jednorazowa'])],
            'payment_date' => ['nullable', 'date']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa produktu jest wymagana',
            'name.min' => 'Nazwa musi mieć co najmniej :min znaki',
            'name.max' => 'Nazwa nie może mieć więcej niż :max znaków.',

            'amount.required' => 'Kwota jest wymagana',
            'amount.numeric' => 'Kwota musi być liczbą',
            'amount.min' => 'Kwota musi wynosić co najmniej :min zł',
            'amount.max' => 'Kwota nie może przekraczać :max zł',

            'is_paid.required' => 'Wybierz status płatności',

            'type.required' => 'Wybierz rodzaj płatności',
            'type.in' => 'Wybrano nieprawidłowy rodzaj płatności',

            'payment_date.date' => 'Wybierz prawidłową datę płatności',
        ];
    }
}
