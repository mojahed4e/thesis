<?php

namespace App\Http\Requests;

use App\Tag;
use App\Item;
use App\Term;
use App\Category;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ApproveRequest extends FormRequest
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
			'supervisor_id' => [
                'required', 'exists:'.(new User)->getTable().',id'
            ],
			'approve_status' => [
                'in:1,2,3'
            ],
            'description' => [
            ],
			'myfile' => [
            ]
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'supervisor_id' => 'assigned supervisor',
			'approve_status' => 'aproval stauts'			
        ];
    }
}
