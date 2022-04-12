<?php

namespace App\Controllers;
use App\Models\User;
use App\Core\{Request, App};

class PagesController
{
    public function home()
    {
        return view('home');
    }

    public function login()
    {
        return view('login');
    } 

    public function register()
    {
        $errors = [];
        $user = new User();
        if(Request::method()=='POST'){
            $user->loadData(Request::getBody());
            if($user->validate() && $user->save()){
                App::get('session')->setFlash('success', 'Thanks for registering');
                redirect('home');
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