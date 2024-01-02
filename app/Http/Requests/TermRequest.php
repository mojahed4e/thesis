<?php

namespace App\Http\Requests;

use App\Term;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'min:3', Rule::unique((new Term)->getTable())->ignore($this->route()->term->id ?? null)
            ],
			'academic_year' => [
                'required', 'min:5'
            ],
            'description' => [
                'nullable', 'min:5'
            ]
        ];
    }
}
