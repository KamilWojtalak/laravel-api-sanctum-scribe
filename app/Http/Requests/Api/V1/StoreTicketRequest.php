<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam data.attributes.title required string The Ticket's title. No-example
 */
class StoreTicketRequest extends FormRequest
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
        // NOTE podobna sturkutra JAK TY wysyłasz dane w api, tak samo oni mają je wysyłać
        $rules = [
            'data.attributes.title' => 'required',
            'data.attributes.description' => 'required',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
        ];

        // NOTE w request możesz sprawdzać na jakim route jesteś
        if ($this->routeIs('tickets.store'))
        {
            $rules['data.relationships.author.data.id'] = 'required|integer';
        }

        return $rules;
    }

    // NOTE można zdefniować messages
    public function messages()
    {
        return [
            'data.attributes.status.in' => 'The data.attributes.status value is invalid. Please use A, C, H, or X.',
        ];
    }
}
