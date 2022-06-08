<?php
namespace App\Core;
//use App\Models\DBModel;

abstract class UserModel// extends DBModel
{
    abstract public function getDisplayName(): string;
}