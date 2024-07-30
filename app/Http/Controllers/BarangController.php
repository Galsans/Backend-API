<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Category;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with(['category', 'divisi.departments', 'history.user'])->get();
        if ($barang->isEmpty()) {
            return response()->json([
                'msg' => 'data belum ada'
            ], 404);
        }
        return response()->json([
            'data' => $barang
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search($asset_id)
    {
        $barang = Barang::where('asset_id', 'LIKE', '%' . $asset_id . '%')->get();
        if ($barang->isEmpty()) {
            return response()->json([
                'msg' => 'data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'msg' => 'data berhasil ditemukan',
            'data' => $barang
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validate = Validator::make($request->all(), [
            "category_id" => 'required|exists:categories,id',
            "divisi_id" => 'required|exists:divisis,id',
            "type_monitor" => 'required',
            "status_penggunaan" => 'required',
            "date_barang_masuk" => 'required|date',
            "kondisi_barang" => 'required',
            "note" => 'required',
            "brand" => 'required',
            "spek_origin" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        // Ambil data divisi dan category
        $divisi = Divisi::find($request->divisi_id);
        $category = Category::find($request->category_id);

        // Periksa apakah divisi dan kategori ditemukan
        if (!$divisi) {
            return response()->json(['msg' => 'Divisi tidak ditemukan'], 404);
        }

        if (!$category) {
            return response()->json(['msg' => 'Kategori tidak ditemukan'], 404);
        }

        // Buat kode asset_id
        $divisionCode = $divisi->departments->kode;
        $categoryCode = $category->kode;

        // Hitung nomor urut barang
        $itemCount = Barang::count() + 1;
        $sequenceNumber = str_pad($itemCount, 3, '0', STR_PAD_LEFT);

        // Ambil bulan dan tahun dari date_barang_masuk
        $date = new \DateTime($request->date_barang_masuk);
        $dateCode = $date->format('m') . $date->format('y');

        // Gabungkan untuk membuat asset_id
        $asset_id = $divisionCode . $categoryCode . $sequenceNumber . $dateCode;

        // Buat record baru di tabel barang
        $input = $request->all();
        $barang = new Barang();
        $barang->asset_id = $asset_id;
        $barang->category_id = $input['category_id'];
        $barang->divisi_id = $input['divisi_id'];
        $barang->type_monitor = $input['type_monitor'];
        $barang->status_penggunaan = $input['status_penggunaan'];
        $barang->date_barang_masuk = $input['date_barang_masuk'];
        $barang->kondisi_barang = $input['kondisi_barang'];
        $barang->note = $input['note'];
        $barang->brand = $input['brand'];
        $barang->spek_origin = $input['spek_origin'];
        $barang->save(); // Simpan barang terlebih dahulu untuk mendapatkan ID

        // Buat data QR Code setelah barang disimpan dan mendapatkan ID
        $qrCodeData = url('/api/barang/' . $barang->id); // Gunakan URL lengkap
        // $qrCodeData = 'http://192.168.1.20/api/barang/' . $barang->id;
        $qrCodePath = 'qrcodes/' . "barcode" . $barang->id . '.svg';

        // Menyimpan svg qrcode dengan Storage di folder public
        if (!Storage::exists('public/qrcodes')) {
            Storage::makeDirectory('public/qrcodes');
        }

        try {
            // Menghasilkan QR code dalam format SVG dan menyimpannya
            $renderer = new ImageRenderer(
                new RendererStyle(300),
                new SvgImageBackEnd()
            );

            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($qrCodeData);

            // Simpan file QR code
            $qrCodeFullPath = storage_path('app/public/' . $qrCodePath);
            file_put_contents($qrCodeFullPath, $qrCode);

            // Pastikan file berhasil disimpan
            if (!file_exists($qrCodeFullPath)) {
                return response()->json(['error' => 'Failed to save QR code image.']);
            }

            // Simpan path QR Code ke database
            $barang->barcode = Storage::url($qrCodePath);
            $barang->save(); // Update record dengan barcode

            return response()->json([
                'msg' => 'Data berhasil disimpan',
                'data' => $barang
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR code: ' . $e->getMessage()], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
            "category_id" => 'exists:categories,id',
            "divisi_id" => 'exists:divisis,id',
            "date_barang_masuk" => 'date',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['msg' => 'Barang tidak ditemukan'], 404);
        }

        // Ambil data divisi dan category jika ada perubahan
        $divisi = $request->has('divisi_id') ? Divisi::find($request->divisi_id) : Divisi::find($barang->divisi_id);
        $category = $request->has('category_id') ? Category::find($request->category_id) : Category::find($barang->category_id);

        // Periksa apakah divisi dan kategori ditemukan
        if (!$divisi) {
            return response()->json(['msg' => 'Divisi tidak ditemukan'], 404);
        }

        if (!$category) {
            return response()->json(['msg' => 'Kategori tidak ditemukan'], 404);
        }

        // Buat kode asset_id baru jika ada perubahan di divisi_id atau category_id
        $updateAssetId = false;
        if ($request->has('divisi_id') || $request->has('category_id')) {
            // Buat kode asset_id baru
            $divisionCode = $divisi->departments->kode;
            $categoryCode = $category->kode;

            // Hitung nomor urut barang
            $itemCount = Barang::count() + 1;
            $sequenceNumber = str_pad($itemCount, 3, '0', STR_PAD_LEFT);

            // Ambil bulan dan tahun dari date_barang_masuk
            $date = $request->has('date_barang_masuk') ? new \DateTime($request->date_barang_masuk) : new \DateTime($barang->date_barang_masuk);
            $dateCode = $date->format('m') . $date->format('y');

            // Gabungkan untuk membuat asset_id
            $asset_id = $divisionCode . $categoryCode . $sequenceNumber . $dateCode;

            // Set asset_id baru
            $barang->asset_id = $asset_id;
            $updateAssetId = true;
        }

        // Update record dengan input baru
        $input = $request->all();
        if ($updateAssetId) {
            $input['asset_id'] = $barang->asset_id;
        }

        $barang->update($input);

        return response()->json([
            'msg' => 'Data berhasil diubah',
            'data' => $barang
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari barang berdasarkan ID
        $barang = Barang::find($id);

        if ($barang === null) {
            return response()->json([
                'msg' => 'Data tidak ditemukan'
            ], 404);
        }

        // Cek apakah barang terhubung dengan tabel lain
        // Misalnya, kita cek tabel `request` yang mungkin memiliki kolom `department_id`
        // Sesuaikan dengan relasi yang ada di aplikasi Anda

        if ($barang->history()->exists()) {  // Ganti `divisi()` dengan relasi yang sesuai
            return response()->json([
                'msg' => 'Data tidak dapat dihapus karena ada relasi dengan tabel lain'
            ], 400);  // 400 Bad Request lebih tepat untuk kondisi ini
        }

        // Hapus barang
        $barang->delete();

        return response()->json([
            'msg' => 'Data berhasil dihapus'
        ], 200);
    }

    public function restore($id)
    {
        $barang = Barang::withTrashed()->find($id);

        if ($barang) {
            $barang->restore();
            return response()->json([
                'msg' => 'Barang berhasil dikembalikan',
                'data' => $barang
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Barang tidak ditemukan',
            ], 404);
        }
    }
}
