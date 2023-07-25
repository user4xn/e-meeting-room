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
            'role_id'   => 1, 
            'full_name' => 'Demo User',
            'email' => 'demouser@gmail.com', 
            'password'  => bcrypt('demouser123'), 
            'phone_number'  => '085229931237',
            'no_sk'   => '-',
            'nip'   => '-',
            'status'   => 'Active',
        ]);
    }
}
