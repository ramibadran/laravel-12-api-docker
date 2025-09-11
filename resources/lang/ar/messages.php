<?php

return [

    // General Messages
    'general' => [
        'unexpected_error'              => 'An unexpected error occurred',
        'success'                       => 'Success',
        'error'                         => 'Error',
        'token_expired'                 => 'Token is Expired',
        'invalid_token_provided'        => 'Invalid Token Provided',
        'validation_error'              => 'Validation error',
        'unauthorized'                  => 'غير مصرح بالدخول',
        'error_parsing_token'           => 'Error parsing token',
        'authorization_header_missing'  => 'Authorization header is missing',
        'user_not_found_in_token'       => 'User not found for the provided token',
        'token_not_provided'            => 'Token not provided',
        'invalid_data_provided'         => 'Invalid data Provided',
        'created'                       => 'Created',
        'invalid_inventory_id_provided' => 'Invalid inventory id provided',
        'too_many_requests'             => 'Too Many Requests',
        'request_key_required'          => 'معرّف الارسال مطلوب',
    ],

    // Validation Messages
    'validation' => [
        'create_user'           => [
                                    'first_name_required'           => 'الاسم الأول مطلوب',
                                    'first_name_max'                => 'يجب ألا يتجاوز الاسم الاول 15 حرفًا',
                                    'first_name_min'                => 'يجب أن يتكون الاسم الاول من 2 أحرف على الأقل',
                                    'first_name_regex'              => 'يجب أن يحتوي الاسم الاول على حروف وأرقام فقط',
                                    'last_name_required'            => 'الاسم الأخير مطلوب',
                                    'last_name_max'                 => 'يجب ألا يتجاوز الاسم الاخير 15 حرفًا',
                                    'last_name_min'                 => 'يجب أن يتكون الاسم الاخير من 2 أحرف على الأقل',
                                    'last_name_regex'               => 'يجب أن يحتوي الاسم الاخير على حروف وأرقام فقط',
                                    'email_required'                => 'البريد الإلكتروني مطلوب',
                                    'email_valid'                   => 'يرجى استخدام عنوان بريد إلكتروني صالح',
                                    'email_unique'                  => 'هذا البريد الإلكتروني مستخدم من قبل',
                                    'password_required'             => 'كلمة السر حقل مطلوب',
                                    'device_id_required'            => 'معرّف الجهاز مطلوب',
                                    'device_token_required'         => 'رمز الجهاز مطلوب',
                                    'device_type_required'          => 'نوع الجهاز مطلوب',
                                    'duplicate_profile'             => 'الملف الشخصي موجود بالفعل',
                                    'lang_required'                 => 'اختيار اللغة مطلوب',
                                    'lang_in'                       => 'يجب أن تكون اللغة إما العربية أو الإنجليزية',

                                   
    
                                ],
        'token'             => [
                                    'device_token_required'         => 'رمز الجهاز مطلوب',
                                    'device_type_required'          => 'نوع الجهاز مطلوب',
                                ],
    ],
];
