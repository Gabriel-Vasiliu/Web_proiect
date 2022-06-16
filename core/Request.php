<?php

namespace App\Core;

class Request
{
    public static function uri()
    {
        // die(var_dump($_SERVER));
        // die(var_dump(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function queryParams()
    {
        $queryParams = [];
        $parsedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        //die(var_dump($parsedUrl));
        if(empty($parsedUrl))
        {
            return $queryParams;
        }

        $values = explode('&', $parsedUrl);
        //die(var_dump($values));
        foreach($values as $value)
        {
            $tmp = explode('=', $value);
            $queryParams[$tmp[0]] = $tmp[1];
        }   
        return $queryParams;
    }

    public static function getBody(){
        foreach($_POST as $key => $value){
            $data[$key] = $value;
        }
        if(!empty($_FILES)){
            $data['image'] = $_FILES['image']['name'];
        }
        return $data;
    }
}