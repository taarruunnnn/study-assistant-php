<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'country' => 'required|string|max:2',
            'university' => 'required|string|max:100',
            'major' => 'required|string|max:30',
            'birth' => 'required|digits:4|integer|min:1990',
            'gender' => 'nullable|string|max:1',
            'password' => 'nullable|string|min:6|confirmed'
        ];
    }
}
