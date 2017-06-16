<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;
use App\User;
use Auth;
class CheckEditProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if(Auth::user()) return true;
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
        $today = date("Y-m-d");
        return [
            'start_date' => "required|date",
            'project_name' => "required|max:225",
            "remarks" => 'max:255',
            'end_date' => 'required|date|after_equal:start_date',
            'overtime_end_date' =>'date|after_equal:end_date',
            'overtime_final_status' =>'numeric|min:0|max:100',
            'time_allocation_status' =>'numeric|min:0|max:100',
        ];
    }
}
