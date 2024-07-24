<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the departments that owns the Divisi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departments()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
