<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class BranchController extends AdminController
{
    //
    public function index(Request $req)
    {
        $this->data['page_title'] = 'Branch List';
        return $this->view('manage-branch', $this->data)
            ->with([
                'page_title' => 'Branch List'
        ]);
    }

    public function createBranch(Request $req)
    {
        $this->data['page_title'] = 'New Branch';
        return $this->view('new-branch', $this->data)
            ->with([
                'page_title' => 'New Branch'
        ]);
    }
}
