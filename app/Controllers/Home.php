<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function debug()
    {
        echo "<pre>";
        var_export($_SERVER);
        echo "</pre>";
    }
}