<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Modul;

class ModulTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=7; $i++){ 
            Modul::create([
                'nama_modul' => 'Modul 1',
                'id_praktikum' => $i,
                'jumlah_bab' => '1',
                'materi' => '',
            ]);
        }
    }
}
