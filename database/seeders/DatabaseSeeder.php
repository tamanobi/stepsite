<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        $groups = ['無料会員', 'シルバー会員', 'ゴールド会員'];
        foreach ($groups as $group) {
            \App\Models\Group::create(['name' => $group]);
        }
        $this->call([
            UsersTableSeeder::class,
        ]);
    }
}
