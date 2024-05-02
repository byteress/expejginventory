<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class ProductController extends AdminController
{
    //
    //
    public function index(Request $req)
    {
        $this->data['page_title'] = 'Branch List';
        return $this->view('manage-product', $this->data)
            ->with([
                'page_title' => 'Branch List'
        ]);
    }

    public function createProduct(Request $req)
    {
        $this->data['page_title'] = 'New Product';
        return $this->view('new-product', $this->data)
            ->with([
                'page_title' => 'New Product'
        ]);
    }
}
