<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentControlller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $department = Department::all();
        return response()->json([
            'msg' => 'data department',
            'data' => $department
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
            'kode' => 'required|max:3|regex:/^[A-Z]+$/',
        ], [
            'name.required' => 'Nama harus diisi.',
            'kode.required' => 'Kode harus diisi.',
            'kode.max' => 'Kode tidak boleh lebih dari 3 huruf.',
            'kode.regex' => 'Kode harus berisi huruf kapital saja.',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        $department = Department::create($request->all());
        return response()->json([
            'msg' => "data berhasil disimpan",
            'data' => $department
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::find($id);
        if ($department == null) {
            return response()->json([
                'msg' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'data' => $department
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
        // Validasi input
        $validate = Validator::make($request->all(), [
            'kode' => 'max:3|regex:/^[A-Z]+$/',
        ], [
            'kode.max' => 'Kode tidak boleh lebih dari 3 huruf.',
            'kode.regex' => 'Kode harus berisi huruf kapital saja.',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        // Cari department berdasarkan ID
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'msg' => 'Department tidak ditemukan.'
            ], 404);
        }

        // Update department dengan data yang valid
        $department->update($request->all());

        return response()->json([
            'msg' => 'Data berhasil diubah',
            'data' => $department
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari department berdasarkan ID
        $department = Department::find($id);

        if ($department === null) {
            return response()->json([
                'msg' => 'Data tidak ditemukan'
            ], 404);
        }

        // Cek apakah department terhubung dengan tabel lain
        // Misalnya, kita cek tabel `request` yang mungkin memiliki kolom `department_id`
        // Sesuaikan dengan relasi yang ada di aplikasi Anda

        if ($department->divisi()->exists()) {  // Ganti `divisi()` dengan relasi yang sesuai
            return response()->json([
                'msg' => 'Data tidak dapat dihapus karena ada relasi dengan tabel lain'
            ], 400);  // 400 Bad Request lebih tepat untuk kondisi ini
        }

        // Hapus department
        $department->delete();

        return response()->json([
            'msg' => 'Data berhasil dihapus'
        ], 200);
    }


    public function restore($id)
    {
        $department = Department::withTrashed()->find($id);

        if ($department) {
            $department->restore();
            return response()->json([
                'msg' => 'Department berhasil dikembalikan',
                'data' => $department
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Department tidak ditemukan',
            ], 404);
        }
    }
}
