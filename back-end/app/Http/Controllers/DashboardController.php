<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $totalUmkm = DB::table('umkm')->count();
        $totalProduct = DB::table('produk')->count();
        $totalAdmin = DB::table('admin')->count();

        return response()->json([
            'status' => 1,
            'total_umkm' => $totalUmkm,
            'total_product' => $totalProduct,
            'total_admin' => $totalAdmin,
        ]);
    }
}
