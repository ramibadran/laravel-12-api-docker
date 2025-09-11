<?php

return [

    // General Messages
    'general' => [
        'unexpected_error'              => 'An unexpected error occurred',
        'success'                       => 'Success',
        'error'                         => 'Error',
        'token_expired'                 => 'Token has Expired',
        'invalid_token_provided'        => 'Invalid Token Provided',
        'validation_error'              => 'Validation error',
        'unauthorized'                  => 'Unauthorized',
        'error_parsing_token'           => 'Error parsing token',
        'authorization_header_missing'  => 'Authorization header is missing',
        'user_not_found_in_token'       => 'User not found for the provided token',
        'token_not_provided'            => 'Token not provided',
        'invalid_data_provided'         => 'Invalid Data Provided',
        'profile_not_exist'             => 'Profile Not Exist',
        'created'                       => 'Created',
        'invalid_inventory_id_provided' => 'Invalid inventory id provided',
        'too_many_requests'             => 'Too Many Requests',
        'request_key_required'          => 'Request key is required',

    ],

    // Validation Messages
    'validation' => [
        'create_user'           => [
                                    'first_name_required'   => 'First name is required',
                                    'first_name_max'        => 'First name must not exceed 15 characters',
                                    'first_name_min'        => 'First name must be at least 2 characters long',
                                    'first_name_regex'      => 'First name must contain only letters and numbers',
                                    'last_name_required'    => 'Last name is required',
                                    'last_name_max'         => 'Last name must not exceed 15 characters',
                                    'last_name_min'         => 'Last name must be at least 2 characters long',
                                    'last_name_regex'       => 'Last name must contain only letters and numbers',
                                    'email_required'        => 'Email is required',
                                    'email_valid'           => 'Please use a valid email address',
                                    'email_unique'          => 'This email address is already in use',
                                    'password_required'     => 'Password field is required',
                                    'device_id_required'    => 'Device ID is required',
                                    'device_token_required' => 'Device token is required',
                                    'device_type_required'  => 'Device type is required',
                                    'duplicate_profile'     => 'Profile already exists',
                                    'lang_required'         => 'Language selection is required',
                                    'lang_in'               => 'Language must be either Arabic or English',
                                ],
        'token'             => [
                                    'device_token_required'         => 'Device token is required',
                                    'device_type_required'          => 'Device type is required',
                                ],                     
    ],
];
