<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'kode_produk' => 'nullable|string',
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
            'foto_1' => 'nullable|image|max:2048',
            'foto_2' => 'nullable|image|max:2048',
            'foto_3' => 'nullable|image|max:2048',
            'id_umkm' => 'nullable|numeric',
        ]);

        try {
            
            $product = [
                'kode_produk' =>  $request->input('kode_produk'),
                'nama_produk' =>  $request->input('nama_produk'),
                'harga' =>  $request->input('harga'),
                'id_umkm' =>  $request->input('id_umkm'),
                'foto_1' => $request->input('foto_1'),
                'foto_2' => $request->input('foto_2'),
                'foto_3' => $request->input('foto_3'),
            ];
    

            foreach (range(1, 3) as $index) {
                $fileKey = 'foto_' . $index;
                if ($request->hasFile($fileKey)) {
                    $product[$fileKey] = $request->file($fileKey)->store('product_photos', 'public');
                }
            }

            $product = DB::table('produk')->insertGetId($product);

            return response()->json([
                'status' => 1,
                'message' => 'Product added successfully',
                'product_id' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to add product: ' . $e->getMessage()
            ]);
        }
    }

    public function fetch(Request $request)
    {
        try {
            $products = DB::table('produk')->where('id_umkm', $request->id)->get();
            
            return response()->json([
                'status' => 1,
                'message' => 'Products fetched successfully',
                'products' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch products: ' . $e->getMessage()
            ]);
        }
    }

    public function getById(Request $request)
    {
        $id = $request->input('id');

        $produk = DB::table('produk')
            ->where('id', $id)
            ->first();

        if ($produk) {
            return response()->json([
                'status' => 1,
                'produk' => $produk
            ]);
        } else {
            return response()->json([
                'status' => 0
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
            ]);

            $id = $request->input('id');



            $produk = DB::table('produk')->where('id', $id)->first();

            if (!$produk) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Produk tidak ditemukan'
                ]);
            }

            foreach (range(1, 3) as $index) {
                $fotoKey = 'foto_' . $index;
                if (!empty($produk->$fotoKey)) {
                    Storage::disk('public')->delete($produk->$fotoKey);
                }
            }

            DB::table('produk')->where('id', $id)->delete();

            return response()->json([
                'status' => 1,
                'id_umkm' => $produk->id_umkm,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
                'kode_produk' => 'nullable|string',
                'nama_produk' => 'required|string',
                'harga' => 'required|numeric',
                'foto_1' => 'nullable|image|max:2048',
                'foto_2' => 'nullable|image|max:2048',
                'foto_3' => 'nullable|image|max:2048',
                'id_umkm' => 'nullable|numeric',
            ]);

            $id = $request->input('id');

            $produk = DB::table('produk')->where('id', $id)->first();

            if (!$produk) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Produk tidak ditemukan'
                ]);
            }

            $updatedProduct = [
                'kode_produk' => $request->input('kode_produk'),
                'nama_produk' => $request->input('nama_produk'),
                'harga' => $request->input('harga'),
                'id_umkm' => $request->input('id_umkm'),
            ];

            foreach (range(1, 3) as $index) {
                $fileKey = 'foto_' . $index;
                if ($request->hasFile($fileKey)) {
                    if (!empty($produk->$fileKey)) {
                        Storage::disk('public')->delete($produk->$fileKey);
                    }
                    $updatedProduct[$fileKey] = $request->file($fileKey)->store('product_photos', 'public');
                }
            }

            DB::table('produk')->where('id', $id)->update($updatedProduct);

            return response()->json([
                'status' => 1,
                'message' => 'Produk berhasil diperbarui',
                'product_id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
            ]);
        }
    }


    public function fetchByUmkmId(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
            ]);

            $idUmkm = $request->input('id');

            $products = DB::table('produk')->where('id_umkm', $idUmkm)->get();

            return response()->json([
                'status' => 1,
                'message' => 'Products fetched successfully',
                'products' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch products by UMKM ID: ' . $e->getMessage()
            ]);
        }
    }

}
