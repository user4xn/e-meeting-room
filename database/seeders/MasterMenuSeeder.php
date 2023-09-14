<?php

namespace Database\Seeders;

use App\Models\MasterMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus =  ['Pengguna', 'Dasbor', 'Master Data', 'Berkas', 'Sewa Ruang Rapat', 'Daftar Pengajuan', 'Peserta Meeting', 'Pengaturan'];

        foreach($menus as $m){
            $menu = new MasterMenu();
            $menu->menu_name = $m;
            $menu->save();
        }
    }
}
