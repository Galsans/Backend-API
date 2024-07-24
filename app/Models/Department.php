<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the divisi for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function divisi()
    {
        return $this->hasMany(Divisi::class);
    }
}
