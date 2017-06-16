<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Auth;
class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->role == 1) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'name' => 'required|max:255',
        'email' => 'unique:users,email|required|max:225',
        'password' => 'required|max:225|confirmed|min:8',
        'password_confirmation' => 'required|min:8',
        'role' => 'min:0|max:1|size:1'
        ];
    }
}
