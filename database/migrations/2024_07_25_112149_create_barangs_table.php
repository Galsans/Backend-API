<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id')->unique();
            $table->string('type_monitor')->nullable();
            $table->enum('status_penggunaan', ['in use', 'not use']);
            $table->date('date_barang_masuk');
            $table->enum('kondisi_barang', ['rusak', 'bagus']);
            $table->text('note')->nullable();
            $table->string('brand')->nullable();
            $table->text('spek_origin')->nullable();
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreignId('divisi_id')->references('id')->on('divisis')->onDelete('cascade');
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
