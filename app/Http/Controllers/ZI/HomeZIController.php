<?php

namespace App\Http\Controllers\ZI;

use PhpParser\Node\Expr\List_;
use App\Models\ZI\ListPenerimaZI;
use App\Http\Controllers\Controller;

class HomeZIController extends Controller
{
    public function home()
    {
        $zi = ListPenerimaZI::all();
        return view('zi.home_zi', compact('zi'));
    }
}
