<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    public $connection = 'mysql2';
    public $table = 'dosen_pengampu';
    public $fillable = ['nama_dosen', 'no_telp'];
}
