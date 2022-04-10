<?php

namespace App\Models;

use Model;

class RegisterModel extends Model
{
    public string $username = '';
    public string $password = '';
    public string $confirmPassword = '';

    public function register(){
        echo 'Creating new user';
    }

    public function rules(): array
    {
        return [
            'username' => [SELF::RULE_REQUIRED],
            'password' => [SELF::RULE_REQUIRED, [SELF::RULE_MIN, 'min' => 8], [SELF::RULE_MAX, 'max' => 24]],
            'confirmPassword' => [SELF::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }
}