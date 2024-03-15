<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UMKMController extends Controller
{
    public function editData(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
                'nama_umkm' => 'required|string|max:255',
                'nama_pemilik' => 'required|string|max:255',
                'personal_kontak' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'alamat' => 'required|string|max:255',
                'kota' => 'nullable|integer', 
                'foto_1' => 'nullable|image|max:2048',
                'foto_2' => 'nullable|image|max:2048',
                'foto_3' => 'nullable|image|max:2048',
            ]);
    
            $id = $validatedData['id'];
    
            $existingUmkm = DB::table('umkm')->where('id', $id)->first();
    
            if (!$existingUmkm) {
                return response()->json([
                    'status' => 0,
                    'message' => 'UMKM tidak ditemukan'
                ]);
            }
    
            $umkm = [
                'nama_umkm' => $validatedData['nama_umkm'],
                'nama_pemilik' => $validatedData['nama_pemilik'],
                'personal_kontak' => $validatedData['personal_kontak'],
                'deskripsi' => $validatedData['deskripsi'],
                'alamat' => $validatedData['alamat'],
            ];
    
            if (isset($validatedData['kota'])) {
                if ($validatedData['kota']) {
                    $cityInfo = DB::table('cities')
                        ->join('provinces', 'cities.prov_id', '=', 'provinces.prov_id')
                        ->where('cities.city_id', $validatedData['kota'])
                        ->select('cities.city_name', 'provinces.prov_name')
                        ->first();
    
                    if ($cityInfo) {
                        $umkm['kota'] = ucwords(strtolower($cityInfo->city_name));
                        $umkm['provinsi'] = ucwords(strtolower($cityInfo->prov_name));
                    }
                } else {
                    $umkm['kota'] = null;
                    $umkm['provinsi'] = null;
                }
            }
    
            foreach (range(1, 3) as $index) {
                $fileKey = 'foto_' . $index;
                if ($request->hasFile($fileKey)) {
                    // Hapus foto lama dari sistem penyimpanan (public disk)
                    if (!empty($existingUmkm->$fileKey)) {
                        Storage::disk('public')->delete($existingUmkm->$fileKey);
                    }
                    // Simpan foto baru
                    $umkm[$fileKey] = $request->file($fileKey)->store('umkm_photos', 'public');
                }
            }
    
            DB::table('umkm')->where('id', $id)->update($umkm);
    
            return response()->json([
                'status' => 1,
                'message' => 'Data UMKM berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Gagal memperbarui data UMKM: ' . $e->getMessage()
            ]);
        }
    }
    


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'personal_kontak' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|integer', 
            'foto_1' => 'required|image|max:2048',
            'foto_2' => 'nullable|image|max:2048',
            'foto_3' => 'nullable|image|max:2048',
        ]);

        $city = DB::table('cities')
            ->join('provinces', 'cities.prov_id', '=', 'provinces.prov_id')
            ->where('cities.city_id', $validatedData['kota'])
            ->select('cities.city_name', 'provinces.prov_name')
            ->first();

        $umkm = [
            'nama_umkm' => $validatedData['nama_umkm'],
            'deskripsi' => $validatedData['deskripsi'],
            'alamat' => $validatedData['alamat'],
            'nama_pemilik' => $validatedData['nama_pemilik'],
            'personal_kontak' => $validatedData['personal_kontak'],
            'kota' => ucwords(strtolower($city->city_name)),
            'provinsi' => ucwords(strtolower($city->prov_name)),
        ];

        foreach (range(1, 3) as $index) {
            $fileKey = 'foto_' . $index;
            if ($request->hasFile($fileKey)) {
                $umkm[$fileKey] = $request->file($fileKey)->store('umkm_photos', 'public');
            }
        }

        DB::table('umkm')->insert($umkm);

        return response()->json([
            'status' => 1
        ]);
    }

    public function fetch()
    {
        $umkm = DB::table('umkm')
            ->select('*')
            ->get();

        return response()->json([
            'status' => 1,
            'umkm' => $umkm
        ]);
    }

    public function getById(Request $request)
    {
        $id = $request->input('id');

        $umkm = DB::table('umkm')
            ->where('id', $id)
            ->first();

        if ($umkm) {
            return response()->json([
                'status' => 1,
                'umkm' => $umkm
            ]);
        } else {
            return response()->json([
                'status' => 0
            ]);
        }
    }

    public function deleteData(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer'
        ]);
    
        $id = $validatedData['id'];
    
        $umkm = DB::table('umkm')->where('id', $id)->first();
    
        if (!$umkm) {
            return response()->json([
                'status' => 0,
                'message' => 'UMKM tidak ditemukan'
            ]);
        }
    
        // Hapus foto_1 jika ada
        if (!empty($umkm->foto_1)) {
            Storage::delete('public/' . $umkm->foto_1);
        }

        // Hapus foto_2 jika ada
        if (!empty($umkm->foto_2)) {
            Storage::delete('public/' . $umkm->foto_2);
        }
    
        // Hapus foto_3 jika ada
        if (!empty($umkm->foto_3)) {
            Storage::delete('public/' . $umkm->foto_3);
        }
    
        DB::table('umkm')->where('id', $id)->delete();
    
        return response()->json([
            'status' => 1,
            'message' => 'Data UMKM berhasil dihapus'
        ]);
    }


}
