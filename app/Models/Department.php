<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
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
