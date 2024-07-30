<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        if ($category->isEmpty()) {
            return response()->json([
                'msg' => 'data belum ada'
            ], 404);
        }
        return response()->json([
            'msg' => 'data category',
            'data' => $category
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
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'kode' => 'required|max:2|regex:/^[A-Z]+$/',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }
        $category = Category::create($request->all());
        return response()->json([
            'msg' => 'data berhasil disimpan',
            'data' => $category
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return response()->json([
                'msg' => 'data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'msg' => 'detail data category',
            'data' => $category
        ], 200);
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
        $validate = Validator::make($request->all(), [
            'kode' => 'max:2|regex:/^[A-Z]+$/',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }
        $category = Category::find($id);
        $category->update($request->all());
        return response()->json([
            'msg' => 'data berhasil diubah',
            'data' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari category berdasarkan ID
        $category = Category::find($id);

        if ($category === null) {
            return response()->json([
                'msg' => 'Data tidak ditemukan'
            ], 404);
        }

        // Cek apakah category terhubung dengan tabel lain
        // Misalnya, kita cek tabel `request` yang mungkin memiliki kolom `department_id`
        // Sesuaikan dengan relasi yang ada di aplikasi Anda

        // if ($category->barang()->exists()) {  // Ganti `barang()` dengan relasi yang sesuai
        //     return response()->json([
        //         'msg' => 'Data tidak dapat dihapus karena ada relasi dengan tabel lain'
        //     ], 400);  // 400 Bad Request lebih tepat untuk kondisi ini
        // }

        // Hapus category
        $category->delete();

        return response()->json([
            'msg' => 'Data berhasil dihapus'
        ], 200);
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->find($id);

        if ($category) {
            $category->restore();
            return response()->json([
                'msg' => 'Category berhasil dikembalikan',
                'data' => $category
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Category tidak ditemukan',
            ], 404);
        }
    }
}
