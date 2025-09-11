<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'         => ['required'],
            'password'      => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.required'        => 'البريد الالكتروني حقل مطلوب',
            'password.required'     => 'كلمة السر حقل مطلوب',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(responseJson(Response::HTTP_PRECONDITION_FAILED,collect($validator->errors()->messages())->flatten()->toArray()));
    }
}
