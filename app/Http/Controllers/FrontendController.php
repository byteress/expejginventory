<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    //
    public function view($view='', $data=[])
    {
        return view('frontend.'.$view, $data);
    }
}
