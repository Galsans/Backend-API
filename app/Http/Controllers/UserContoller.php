<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserContoller extends Controller
{
    public function index()
    {
        $user = User::with('divisi')->get();
        return response()->json([
            'msg' => 'data user',
            'data' => $user
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'divisi_id' => 'required|exists:divisis,id',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['kode'] = Str::upper(Str::random(5));
        $user = User::create($input);

        return response()->json([
            'msg' => 'data berhasil disimpan',
            'data' => $user
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'divisi_id' => 'required|exists:divisis,id',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }
        $user = User::find($id);
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $user->update($input);

        return response()->json([
            'msg' => 'data berhasil diubah',
            'data' => $user
        ], 200);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json([
                'msg' => 'data tidak diitemukan'
            ], 404);
        } else {
            $user->delete();
            return response()->json([
                'msg' => 'data berhasil dihapus'
            ], 200);
        }
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if ($user) {
            $user->restore();
            return response()->json([
                'msg' => 'User berhasil dikembalikan',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'msg' => 'User tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showBarang(string $id)
    {
        $barang = Barang::with(['category', 'divisi'])->find($id);
        if ($barang == null) {
            return response()->json([
                'msg' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'data' => $barang
        ], 200);
    }
}
