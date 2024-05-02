<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;

class AuthController extends FrontendController
{
    //
    public function login()
    {
        $this->data['page_title'] = 'Login';
        return $this->view('login', $this->data);
    }
}
