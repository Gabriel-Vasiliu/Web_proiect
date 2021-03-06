<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\LoginForm;
use App\Core\Request;
use App\Core\App;
use App\Models\Bottle;

class PagesController
{
    public function home()
    {
        return view('home');
    }

    public function login()
    {
        $loginForm = new LoginForm();
        if(Request::method()=='POST'){
            $loginForm->loadData(Request::getBody());
            if($loginForm->validate() && $loginForm->login()){
                redirect('/');
                //App::login();
                return;
            }
        }
        return view('login', [
            'model' => $loginForm
        ]);
    } 

    public function register()
    {
        $errors = [];
        $user = new User();
        if(Request::method()=='POST'){
            $user->loadData(Request::getBody());
            if($user->validate() && $user->save()){
                App::get('session')->setFlash('success', 'Thanks for registering');
                redirect('/');
                exit;
            }
            return view('register', [
                'model' => $user
            ]);
        }
        return view('register', [
            'model' => $user
        ]);
    }

    public function logout()
    {
        App::logout();
        redirect('/');
    }

    public function edit()
    {
        return view('edit');
    }

    public function search($queryParams)
    {
        if(!empty($queryParams)){
            $bottles = App::get('database')->selectBottlesFilteredData('bottles',$queryParams['type'],$queryParams['value'],$queryParams['country'], $queryParams);
            array_walk($bottles, function($bottle){
                $bottle->with('user');
            });
            $queryParams = json_encode($queryParams);
            //die(var_dump($bottles));       
            return json_encode($bottles);
        }else {
            //$bottles = App::get('database')->selectAllData('bottles');
            //die(var_dump($bottles));
            $bottles = App::get('database')->selectAllData('bottles', Bottle::class);
            //die(var_dump($bottles[0]));
            return view('search', [
                'bottles' => $bottles,
                'queryParams' => $queryParams
            ]);
        }
    }

    public function statistics()
    {
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        $types = array();
        $bestBottle = new Bottle();
        $maxValue = 0;
        foreach($userBottles as $bottle){
            if($bottle->value > $maxValue){
                $maxValue = $bottle->value;
                $bestBottle->type = $bottle->type;
                $bestBottle->image = $bottle->image;
                $bestBottle->value = $bottle->value;
                $bestBottle->country = $bottle->country;
            }
            $ok = 1;
            foreach($types as $type=>$t){
                if($type == $bottle->type){
                    $ok=0;
                    $types[$type] = $t + 1;
                }
            }
            if($ok == 1){
                $types[$bottle->type] = 1;
            }
        }
        arsort($types);
        return view('statistics', [
            'bottles' => $userBottles,
            'bestBottle' => $bestBottle,
            'types' => $types,
            'nrBottles' => sizeof($userBottles)
        ]);
    }

    public function top()
    {
        $bottles = App::get('database')->selectMostValuableBottles('bottles', 'user_bottles', 'users');
        $rss = "<?xml version='1.0' encoding='UTF-8'?>";
        $rss .= "<rss version='2.0'>";
        $rss .= "<channel>";
        $rss .= "<title>Collecting Bottles</title>";
        $rss .= "<description>Collecting Bottles - Top 10</description>";
        $rss .= "<language>en-us</language>";
        $rss .= "<items>";
        
        foreach($bottles as $bottle){
            $rss .= "<item>
            <type>$bottle->type</type>
            <value>$bottle->value</value>
            <country>$bottle->country</country>
            <username>$bottle->username</username>
            </item>";
        }

        $rss .= "</items>";
        $rss .= "</channel></rss>";
        return view('top', [
            'bottles' => $bottles,
            'rss' => $rss
        ]);
    }

    public function manage()
    {
        $bottle = new Bottle();
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        //die(var_dump($userBottles));
        if(!is_dir("./public")){
            mkdir("./public", 0777, true);
        }

        $directory_path = "./public/" . App::$user->username;
        if(!is_dir($directory_path)){
            mkdir($directory_path, 0777, true);
        }

        return view('manage', [
            'userBottles' => $userBottles,
            'newBottlesRequests' => App::get('database')->getNewBottlesRequests(),
            'users' => App::get('database')->getUsersNamesFromRequests(),
            'usersWithId' => App::get('database')->getDistinctUsersNamesWithIdFromRequests(),
            'usersWithBottlesRows' => App::get('database')->getUsersIdsWithBottles(),
            'usersIdWithBottles' => App::get('database')->getUsersIdsWithBottles()
        ]);
    }

        public function add()
    {   
        $requestBody = Request::getBody();
        $bottle = new Bottle();
        if(!empty($requestBody)){
            $bottle->loadData($requestBody);
            $extension = explode('.', $_FILES['image']['name'])[1];
            $bottle->image = substr(sha1($_FILES['image']['name']), 0, 15) . '.' . $extension;
            if($bottle->validate() && $bottle->save()){
                if(isset($_FILES['image'])){
                    move_uploaded_file($_FILES['image']['tmp_name'], './public/' . App::$user->username . '/' . $bottle->image);
                }
                App::get('database')->insertBottleInUserBottlesTable();
                $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
                //die(var_dump($userBottles));
                //App::get('session')->setFlash('success', 'Thanks for registering');
                return json_encode($userBottles);
            }
        }

        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        //die(var_dump($userBottles));
        return view('manage', [
            'userBottles' => $userBottles
        ]);
    }

    public function delete(){
        $requestBody = Request::getBody();
        if(!empty($requestBody)){
            App::get('database')->deleteBottle($requestBody['id']);
            $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
            return json_encode($userBottles);
        }
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        //die(var_dump($userBottles));
        return view('manage', [
            'userBottles' => $userBottles
        ]);
    }

    public function update(){
        $requestBody = Request::getBody();
        //die(var_dump($requestBody));
        if(!empty($requestBody)){
            App::get('database')->updateBottle($requestBody);
            $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
            return json_encode($userBottles);
        }
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        //die(var_dump($userBottles));
        return view('manage', [
            'userBottles' => $userBottles
        ]);
    }

    public function send(){
        $requestBody = Request::getBody();
        if(!empty($requestBody)){
            App::get('database')->insertInTransferBottlesTable($requestBody);
            $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
            return json_encode($userBottles);
        }
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        //die(var_dump($userBottles));
        return view('manage', [
            'userBottles' => $userBottles
        ]);
    }

    public function acceptBottles(){
        $requestBody = Request::getBody();
        $bottlesIds = json_decode($requestBody['rows']);
        $sendingFrom = User::findOne(['id' => $requestBody['userId']], 'users')->username;
        foreach($bottlesIds as $bottleId){
            $bottle = Bottle::findOne(['id' => $bottleId], 'bottles');
            $path = $_SERVER["SCRIPT_FILENAME"];
            $path = preg_replace("/index.php/", "", $path);
            rename($path . "public/{$sendingFrom}/{$bottle->image}", $path.'public/' . App::$user->username . '/' . $bottle->image);
            unlink($path . "public/{$sendingFrom}/{$bottle->image}");
        }
        App::get('database')->acceptBottlesFromTransferBottlesTable($requestBody);
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        //die(var_dump($userBottles));
        return json_encode($userBottles);
    }

    public function doNotAcceptBottles(){
        App::get('database')->deleteBottlesFromTransferBottlesTable();
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        return json_encode($userBottles);
    }
}