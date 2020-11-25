<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get all temperature points for this location
     */
    public function temps()
    {
        return $this->hasMany('\App\Models\Temperature');
    }
}
