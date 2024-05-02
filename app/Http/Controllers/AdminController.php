<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function view($view='', $data=[])
    {
        $data = $this->data;
        return view('admin.'.$view, $data);
    }
}
