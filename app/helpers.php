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

if (!function_exists('warningMessage')) {
    function warningMessage()
    {
        return '
            <div style="
                font-family: Arial, sans-serif;
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 8px;
                max-width: 400px;
                margin: 40px auto;
                text-align: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            ">
                <h2 style="margin-top: 0;">&#9888; Access Denied</h2>
        ';
    }

}