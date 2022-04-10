<?php

namespace App\Controllers;

use App\Models\RegisterModel;
use Request;

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
        $registerModel = new RegisterModel();
        if(Request::method()=='POST'){
            $registerModel->loadData(Request::getBody());
            if($registerModel->validate() && $registerModel->register()){
                return 'Success';
            }

            return view('register', [
                'model' => $registerModel
            ]);
        }
        return view('register', [
            'model' => $registerModel
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