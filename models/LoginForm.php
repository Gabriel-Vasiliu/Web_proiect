<?php

namespace App\Models;
use App\Models\Model;
use App\Models\User;
use App\Core\App;
class LoginForm extends Model
{
    public string $username='';
    public string $password='';
    public function rules(): array
    {
        return [
            'username' => [SELF::RULE_REQUIRED],
            'password' => [SELF::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'username' => 'Your Username',
            'password' => 'Password'
        ];
    }

    public function login()
    {
        $user = User::findOne(['username' => $this->username]);
        if(!$user){
            $this->addError('username', 'User does not exist with this username');
            return false;
        }
        if(!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }
        return App::login($user);
    }
}