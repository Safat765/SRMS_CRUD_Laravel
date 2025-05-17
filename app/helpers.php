<?php

if (!function_exists('p')) {
    function p($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists('getRole')) {
    function getRole()
    {
        $userType = Illuminate\Support\Facades\Session::get('user_type');
        if ($userType == App\Models\User::USER_TYPE_ADMIN) {
            return 'admin';
        } elseif ($userType == App\Models\User::USER_TYPE_INSTRUCTOR) {
            return 'instructor';
        } elseif ($userType == App\Models\User::USER_TYPE_STUDENT) {
            return 'students';
        }
    }
}