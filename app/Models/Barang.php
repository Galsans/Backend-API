<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the category that owns the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class)->with('departments');
    }

    /**
     * Get all of the history for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasOne(History::class)->with('user')
            ->latest('created_at'); // Mengambil data terbaru berdasarkan created_at
    }
}
