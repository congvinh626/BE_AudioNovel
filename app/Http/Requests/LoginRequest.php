<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required|min:6',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'statusCode' => 400,
            'message' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'username.required' => 'Tên đăng nhập bắt buộc nhập!',
            'password.required' => 'Mật khảu bắt buộc nhập!',
            'password.min' => 'Mật khẩu quá ngắn',
        ];
    }
}
