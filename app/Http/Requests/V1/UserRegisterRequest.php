<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'min:2' ,'max:15','regex:/^[a-zA-Z0-9]+$/'],
            'last_name'     => ['required', 'string', 'min:2' ,'max:15','regex:/^[a-zA-Z0-9]+$/'],
            'email'         => ['required','unique:users,email','email'],
            'device_token'  => ['required'],
            'device_type'   => ['required'],
            'password'      => ['required'],
            //'device_id'     => ['required'],
            'device_token'  => ['required'],
            'device_type'   => ['required'],
            //'lang'          => ['required','in:ar,en'],
            'request_key'   => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'   => __('messages.validation.create_user.first_name_required'),
            'first_name.max'        => __('messages.validation.create_user.first_name_max'),
            'first_name.min'        => __('messages.validation.create_user.first_name_min'),
            'first_name.regex'      => __('messages.validation.create_user.first_name_regex'),
            'last_name.required'    => __('messages.validation.create_user.last_name_required'),
            'last_name.max'         => __('messages.validation.create_user.last_name_max'),
            'last_name.min'         => __('messages.validation.create_user.last_name_min'),
            'last_name.regex'       => __('messages.validation.create_user.last_name_regex'),
            'email.required'        => __('messages.validation.create_user.email_required'),
            'email.email'           => __('messages.validation.create_user.email_valid'),
            'email.unique'          => __('messages.validation.create_user.email_unique'),
            'password.required'     => __('messages.validation.create_user.password_required'),
            //'device_id.required'    => __('messages.validation.create_user.device_id_required'),
            'device_token.required' => __('messages.validation.create_user.device_token_required'),
            'device_type.required'  => __('messages.validation.create_user.device_type_required'),
            // 'lang.required'         => __('messages.validation.create_user.lang_required'),
            // 'lang.in'               => __('messages.validation.create_user.lang_in'),
            'request_key.required'  => __('messages.general.request_key_required'),
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(responseJson(Response::HTTP_PRECONDITION_FAILED,collect($validator->errors()->messages())->flatten()->toArray()));
    }
}
