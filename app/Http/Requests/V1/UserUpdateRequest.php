<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserUpdateRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'   => 'الاسم الاول حقل مطلوب',
            'first_name.max'        => 'يجب ألا يتجاوز الاسم الاول 15 حرفًا',
            'first_name.min'        => 'يجب أن يتكون الاسم الاول من 2 أحرف على الأقل',
            'first_name.regex'      => 'يجب أن يحتوي الاسم الاول على حروف وأرقام فقط',
            'last_name.required'    => 'الاسم الاخير حقل مطلوب',
            'last_name.max'         => 'يجب ألا يتجاوز الاسم الاخير 15 حرفًا',
            'last_name.min'         => 'يجب أن يتكون الاسم الاخير من 2 أحرف على الأقل',
            'last_name.regex'       => 'يجب أن يحتوي الاسم الاخير على حروف وأرقام فقط',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(responseJson(Response::HTTP_PRECONDITION_FAILED,collect($validator->errors()->messages())->flatten()->toArray()));
    }
}
