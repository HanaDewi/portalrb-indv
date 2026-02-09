<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    /**
     * Get all instansi list for dropdown
     */
    public function index(Request $request)
    {
        try {
            $instansi = KlpdInstansi::select('id', 'name', 'group')
                ->whereNull('deleted_at')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json($instansi);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data instansi: ' . $e->getMessage()
            ], 500);
        }
    }
}