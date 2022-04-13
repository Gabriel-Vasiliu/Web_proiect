<?php
namespace App\Core;
class Session
{
    protected const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[SELF::FLASH_KEY] ?? [];
        foreach($flashMessages as $key => &$flashMessage){
            //Mark to be removed
            $flashMessage['remove'] = true;
        }
        $_SESSION[SELF::FLASH_KEY] = $flashMessages;
        //var_dump($_SESSION[SELF::FLASH_KEY]);
    }

    public function setFlash($key, $message)
    {
        $_SESSION[SELF::FLASH_KEY][$key] = [
            'removed' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[SELF::FLASH_KEY] ?? [];
        foreach($flashMessages as $key => &$flashMessage){
            if($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[SELF::FLASH_KEY] = $flashMessages;
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key] ?? false; 
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }
}