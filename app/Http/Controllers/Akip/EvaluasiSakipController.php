<?php

namespace App\Http\Controllers\Akip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluasiSakipController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            foreach (allowed_url('akip') as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    public function evaluasi_sakip()
    {
        $data['title'] = 'Evaluasi Sakip';
        return view('akip.evaluasi_sakip', $data);
    }
}
