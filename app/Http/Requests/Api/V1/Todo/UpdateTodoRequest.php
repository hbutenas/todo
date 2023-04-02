<?php

namespace App\Http\Requests\Api\V1\Todo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTodoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'min:8'],
            'description' => ['string', 'min:25'],
            'status' => ['string', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
        ];
    }
}
