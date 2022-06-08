<?php

namespace App\Models;
use App\Models\DBModel;

class Bottle extends DBModel{

    public string $type = '';
    public string $image = '';
    public int $value = 0;
    public string $country = '';
    
    public function tableName(): string
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