<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\LoginForm;
use App\Core\Request;
use App\Core\App;

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

    public function search()
    {
        return view('search');
    }

    public function statistics()
    {
        return view('statistics');
    }

    public function top()
    {
        return view('top');
    }
}