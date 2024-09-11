<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            // 'type' => 'required|numeric|max:2',

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
            'username.unique' => 'Tên đăng nhập đã tồn tại!',
            'password.required' => 'Mật khẩu bắt buộc nhập!',
            'password.min' => 'Mật khẩu quá ngắn',
            'email.required' => 'Email bắt buộc nhập!',
            'email.email' => 'Bắt buộc phải là email!',
            'email.unique' => 'Email đã tồn tại!',
            'type.required' => 'Kiểu người dùng bắt buộc nhập!',
            'type.numeric' => 'Kiểu dữ liệu không hợp lệ!',
            'type.max' => 'Kiểu dữ liệu không hợp lệ!',

        ];
    }
}
