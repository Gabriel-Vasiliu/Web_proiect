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
            $bottles = App::get('database')->selectBottlesFilteredData('bottles',123,123,123);
            $queryParams = json_encode($queryParams);
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
        return view('statistics');
    }

    public function top()
    {
        $bottles = App::get('database')->selectAllData('bottles');
        return view('top', [
            'bottles' => $bottles
        ]);
    }

    public function manage()
    {
        $userBottles = App::get('database')->selectUserBottles(App::$user->username, 'bottles', 'user_bottles', 'users');
        return view('manage', [
            'userBottles' => $userBottles
        ]);
    }

    public function add()
    {
        $bottle = new Bottle();
        if(Request::method()=='POST'){
            $bottle->loadData(Request::getBody());
            if($bottle->validate() && $bottle->save()){
                App::get('session')->setFlash('success', 'Thanks for registering');
                redirect('/');
                exit;
            }
        }
        return view('add');
    }
}