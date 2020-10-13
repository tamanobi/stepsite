<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 開発用ユーザーを定義
        \App\Models\User::create([
            'name' => 'dev',
            'email' => 'dev@example.com',
            'password' => Hash::make('devdev'), // この場合、「my_secure_password」でログインできる
            'remember_token' => Str::random(10),
        ]);        
    }
}
