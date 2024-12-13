<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class UsersController extends AdminController
{
    //
    public function index(Request $req)
    {
        $this->data['page_title'] = 'Users List';
        return $this->view('manage-users', $this->data)
            ->with([
                'page_title' => 'Users List'
        ]);
    }
    public function createUser(Request $req)
    {
        $this->data['page_title'] = 'New User';
        return $this->view('new-user', $this->data)
            ->with([
                'page_title' => 'New User'
        ]);
    }
}
