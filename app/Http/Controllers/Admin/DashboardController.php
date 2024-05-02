<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    //
    public function index(Request $req)
    {
        $this->data['page_title'] = 'Dashboard';
        return $this->view('dashboard', $this->data)
            ->with([
                'page_title' => 'Dashboard'
        ]);
    }
}
