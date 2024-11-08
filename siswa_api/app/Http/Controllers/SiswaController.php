<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Log;

class SiswaController extends Controller
{
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'kelas' => ['required', 'string', 'regex:/^[XVI]{1,3}\sIPA\s\d$/'],
            'umur' => ['required', 'integer', 'between:6,18'],
        ]);

        try {
            $siswa = Siswa::create($validatedData);
            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            Log::error('Error creating data: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data siswa.'], 500);
        }
    }

    

    public function show($id)
    {
        try {
            return Siswa::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $validatedData = $request->validate([
                'nama' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
                'kelas' => ['sometimes', 'required', 'string', 'regex:/^[XVI]{1,3}\sIPA\s\d$/'],
                'umur' => ['sometimes', 'required', 'integer', 'between:6,18'],
            ]);
            $siswa->update($validatedData);

            return response()->json($siswa);
        } catch (\Exception $e) {
            Log::error('Error updating data: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }



    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting data: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }

}
