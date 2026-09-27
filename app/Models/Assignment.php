<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // KITA GANTI $fillable LAMA DENGAN $guarded
    // $guarded = ['id'] artinya semua kolom BOLEH diisi KECUALI id.
    // Ini otomatis mengizinkan: project_id, user_id, qty_assigned, dll.
    protected $guarded = ['id'];

    // Relasi ke User (Penjahit)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Project (Project Induk) - INI YANG BARU
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
