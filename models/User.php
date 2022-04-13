<?php

namespace App\Models;
use App\Core\UserModel;
use App\Models\DBModel;
class User extends DBModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    public string $username = '';
    public int $status = SELF::STATUS_INACTIVE;
    public string $password = '';
    public string $confirmPassword = '';
    public function tableName(): string
    {
        return 'users';
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function save(){
        $this->status = SELF::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        parent::save();
        
    }

    public function rules(): array
    {
        return [
            'username' => [SELF::RULE_REQUIRED, [SELF::RULE_UNIQUE, 'class' => SELF::class]],
            'password' => [SELF::RULE_REQUIRED, [SELF::RULE_MIN, 'min' => 8], [SELF::RULE_MAX, 'max' => 24]],
            'confirmPassword' => [SELF::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function attributes(): array
    {
        return ['username', 'password', 'status'];
    }

    public function labels(): array
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password'
        ];
    }

    public function getDisplayName(): string
    {
        return $this->username;
    }
}