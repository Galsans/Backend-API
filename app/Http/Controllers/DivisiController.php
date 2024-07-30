<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $divisi = Divisi::with('departments')->get();

            if ($divisi->isEmpty()) {
                return response()->json([
                    'msg' => 'Data divisi belum ada',
                ], 422);
            }

            return response()->json([
                'msg' => 'Data divisi',
                'data' => $divisi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
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
            "name" => 'required',
            "department_id" => 'required|exists:departments,id',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }
        $divisi = Divisi::create($request->all());
        return response()->json([
            'msg' => 'berhasil menyimpan data',
            'data' => $divisi
        ], 200);
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
        $validate = Validator::make($request->all(), [
            "department_id" => 'exists:departments,id',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }
        $divisi = Divisi::find($id);
        $divisi->update($request->all());
        return response()->json([
            'msg' => 'berhasil mengubah data',
            'data' => $divisi
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $divisi = Divisi::find($id);

        if ($divisi == null) {
            return response()->json([
                'msg' => 'data tidak ditemukan',
            ], 404);
        }
        return response()->json([
            'msg' => 'data berhasil dihapus'
        ], 200);
    }

    public function restore($id)
    {
        $divisi = Divisi::withTrashed()->find($id);

        if ($divisi) {
            $divisi->restore();
            return response()->json([
                'msg' => 'Divisi berhasil dikembalikan',
                'data' => $divisi
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Divisi tidak ditemukan',
            ], 404);
        }
    }
}
