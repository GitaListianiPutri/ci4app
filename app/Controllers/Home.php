<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    // method index
    {
        return view('welcome_message');
        // memanggil file welcome_message yg ada di fold view
    }

}
