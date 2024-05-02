<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class SupplierController extends AdminController
{
    //
    public function index(Request $req)
    {
        $this->data['page_title'] = 'Supplier List';
        return $this->view('manage-supplier', $this->data)
            ->with([
                'page_title' => 'Supplier List'
        ]);
    }
    public function createSupplier(Request $req)
    {
        $this->data['page_title'] = 'New Supplier';
        return $this->view('new-supplier', $this->data)
            ->with([
                'page_title' => 'New Supplier'
        ]);
    }
}
