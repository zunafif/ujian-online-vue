<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Praktikum extends Model
{
    protected $table ='praktikum';
    protected $fillable=['nama', 'periode','kode','status'];
}
