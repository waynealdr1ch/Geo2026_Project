<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;

class Project extends Model
{
    use HasFactory;

    // Kita izinkan semua kolom diisi
    protected $guarded = ['id'];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
