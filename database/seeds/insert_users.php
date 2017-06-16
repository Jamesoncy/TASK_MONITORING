<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class insert_users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $password = "password";
        DB::table('users')->insert([ 
                'password' => Hash::make($password),
                'name' => $faker->name,
                'email' => "JamesRoncy13@gmail.com",
                "role" => 1

        ]);
    }
}
