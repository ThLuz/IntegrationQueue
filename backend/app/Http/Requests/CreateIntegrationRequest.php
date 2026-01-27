<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateIntegrationRequest extends FormRequest
{
    /**
     * Autoriza todos os requests.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para os campos.
     */
    public function rules(): array
    {
        return [
            'external_id' => 'required|string',
            'nome'        => 'required|string',
            'cpf'         => 'required|digits:11',
            'email'       => 'required|email',
        ];
    }

    /**
     * Método que força a validação a usar os dados do JSON enviado.
     */
    public function validationData(): array
    {
        // Pega todos os dados do JSON enviado no corpo
        return $this->json()->all();
    }

    /**
     * Método que retorna os erros no formato JSON.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
