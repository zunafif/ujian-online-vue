<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Praktikum;

class PraktikumTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nama = ['Sistem Operasi','Jaringan Komputer', 'Basis Data', 'Pemrograman Berbasis Objek', 'Struktur Data', 'Bahasa Pemrograman Java', 'Multimedia'];
        foreach($nama as $prak){
            Praktikum::create([
                'nama' => $prak,
                'periode' => 'XX',
                'kode' => '',
                'status' => 'nonaktif',
            ]);
        }
    }
}
