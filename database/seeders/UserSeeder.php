<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;
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
        $user = new User();
        $user->username = 'esuperadmin';
        $user->password = bcrypt('suksesemeeting2023');
        $user->email = "bagus.candrabudi@gmail.com";
        $user->role = "Admin";
        $user->status = "active";
        $user->save();
        $user->fresh();

        $user_detail = new UserDetail();
        $user_detail->user_id = $user->id;
        $user_detail->nip = "1234567890";
        $user_detail->name = "Admin KKP";
        $user_detail->phone_number = "085229931237";
        $user_detail->address = "JL PP Imam TP, Bobotsari, Purbalingga, Jawa Tengah";
        $user_detail->save();
    }
}
