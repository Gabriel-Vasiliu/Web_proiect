<?php

namespace App\Controllers;

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
        return view('register');
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