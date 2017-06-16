<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Auth;
use Illuminate\Validation\Factory as ValidationFactory;
class ProjectDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->role == 0) return true;
        return false;
    }

    public function __construct(ValidationFactory $validationFactory)
    {

        $validationFactory->extend(
            'after_equal',
            function ($attribute, $value, $parameters, $validator) {
                return strtotime($validator->getData()[$parameters[0]]) <= strtotime($value);
            },
            'End Date Must Be Greater Than Or Equal to Start Date..!'
        );


    }

    public function rules()
    {
        return [
            'project_name' => 'required|max:255',
            'start_date' => "required|date",
            'end_date' => 'required|date|after_equal:start_date'
        ];
    }
}
