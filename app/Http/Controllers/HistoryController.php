<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $barangId)
    {
        // Ambil data history yang sesuai dengan barang_id dari URL
        $history = History::with('barang', 'user') // Memuat relasi barang
            ->where('barang_id', $barangId) // Filter berdasarkan barang_id
            ->get();

        // Periksa apakah data history ditemukan
        if ($history->isEmpty()) {
            return response()->json([
                'msg' => 'No history found for the given barang_id',
                'data' => null
            ], 404);
        }

        return response()->json([
            'msg' => 'data history',
            'data' => $history
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $barangId)
    {
        // Validasi input
        $validate = Validator::make($request->all(), [
            "spek_upgraded" => "required",
            "lokasi" => "required",
            "user_id" => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        // Validasi bahwa barang_id ada dalam tabel barang
        $barang = Barang::find($barangId);
        if (!$barang) {
            return response()->json([
                'msg' => 'Invalid barang_id',
                'data' => null
            ], 404);
        }

        // Persiapkan input data
        $input = $request->all();
        $input['barang_id'] = $barangId; // Ambil barang_id langsung dari URL

        // Simpan data history
        $history = History::create($input);

        return response()->json([
            'msg' => 'Data berhasil disimpan',
            'data' => $history
        ], 201); // Status 201 untuk Created
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
