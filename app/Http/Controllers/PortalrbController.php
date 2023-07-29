<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalrbController extends Controller
{
    public function index()
    {
        return view('Home', [
            "title" => "Portal RB",
        ]);
    }
}
