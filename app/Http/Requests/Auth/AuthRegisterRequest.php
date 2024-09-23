<?php

namespace App\Http\Requests\Auth;

use App\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;



class AuthRegisterRequest extends FormRequest
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
        return [
            'name' => 'required',
            'phone'=> ['required', 'unique:users,phone'],
            'password' => 'required',
            'role' => ['required', new Enum(UserRole::class)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

    public function messages()
    {
        return [
            'name.required' => 'هذا الحقل مطلوب',
            'phone.required' => 'هذا الحقل مطلوب',
            'phone.unique' => 'هذا الرقم موجود بالفعل',
            'password.required' => 'هذا الحقل مطلوب',
            'role.required' => 'هذا الحقل مطلوب',
        ];
    }

}
