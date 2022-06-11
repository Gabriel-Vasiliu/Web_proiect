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
            $queryParams = json_encode($queryParams);
            //die(var_dump($bottles));       
            return json_encode($bottles);
        }else {
            //$bottles = App::get('database')->selectAllData('bottles');
            //die(var_dump($bottles));
            $bottles = App::get('database')->selectAllData('bottles');
            
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
        $bottles = App::get('database')->selectMostValuableBottles('bottles');
        return view('top', [
            'bottles' => $bottles
        ]);
    }

    public function manage($queryParams)
    {        
        $bottle = new Bottle();
        if(!empty($queryParams)){
            $bottle->loadData($queryParams);
            //die(var_dump($bottle->image));
            if($bottle->validate() && $bottle->save()){
                App::get('database')->insertBottleInUserBottlesTable();
                $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
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
}