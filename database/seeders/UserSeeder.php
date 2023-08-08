<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Hashing\BcryptHasher;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'esuperadmin', 
            'password'  => bcrypt('suksesemeeting2023'), 
            'email' => 'bagus.candrabudi@gmail.com',
            'role'   => 'Admin',
            'status'   => 'active',
        ]);
    }
}
