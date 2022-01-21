<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'modul';
    protected $fillable = ['nama_modul', 'id_praktikum', 'jumlah_bab', 'materi'];
}
