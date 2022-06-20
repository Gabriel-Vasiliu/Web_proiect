<?php

namespace App\Models;

use App\Core\App;
use App\Models\DBModel;
use PDO;

class Bottle extends DBModel{

    public string $type = '';
    public string $image = '';
    public int $value = 0;
    public string $country = '';
    public $user = '';

    
    public static function tableName(): string
    {
        return 'bottles';
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function save(){
        return parent::save();
    }

    public function rules(): array
    {
        return [
            'type' => [SELF::RULE_REQUIRED],
            'image' => [SELF::RULE_REQUIRED],
            'value' => [SELF::RULE_REQUIRED],
            'country' => [SELF::RULE_REQUIRED]
        ];
    }

    public function attributes(): array
    {
        return ['type', 'image', 'value', 'country'];
    }

    public function user()
    {
        $pdo = App::get('database')->getPdo();
        $sql="SELECT * FROM user_bottles WHERE bottle_id=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $this->id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
 
        if(empty($result))
        {
            return new User();
        }

        $sql = "SELECT id, username FROM users WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $result[0]->user_id);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        // die(var_dump($user)); 
        
        return $user;
    }

    public function labels(): array
    {
        return [
            'type' => 'Type',
            'image' => 'Image',
            'value' => 'Value',
            'country' => 'Country'
        ];
    }
}