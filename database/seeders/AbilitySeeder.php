<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = array(
            [
                'name'  => "View",
            ],[
                'name'  => 'Create'
            ],[
                'name'  => 'Edit'
            ],[
                'name'  => 'Delete'
            ]
        );

        foreach($abilities as $ability){
            Ability::create([
                'name'  => $ability['name']
            ]);
        }
    }
}
