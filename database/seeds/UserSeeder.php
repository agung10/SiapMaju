<?php

use Illuminate\Database\Seeder;
use App\User;

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
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'remember_token' => Str::random(10),
            'picture' => 'admin.jpg',
            'is_admin' => true
        ]);

        User::create([
            'username' => 'user',
            'email' => 'user@mail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'remember_token' => Str::random(10),
            'picture' => 'user1.jpg',
            'is_admin' => false
        ]);

        // User::create([
        //     'username' => 'angga',
        //     'email' => 'angga@mail.com',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('123456'),
        //     'remember_token' => Str::random(10),
        //     'picture' => 'admin.jpg',
        //     'is_admin' => true,
        //     'anggota_keluarga_id' => 1
        // ]);

        // User::create([
        //     'username' => 'sadewa',
        //     'email' => 'sadewa@mail.com',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('123456'),
        //     'remember_token' => Str::random(10),
        //     'picture' => 'admin.jpg',
        //     'is_admin' => true,
        //     'anggota_keluarga_id' => 2
        // ]);

        // User::create([
        //     'username' => 'ratma',
        //     'email' => 'ratma@mail.com',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('123456'),
        //     'remember_token' => Str::random(10),
        //     'picture' => 'admin.jpg',
        //     'is_admin' => true,
        //     'anggota_keluarga_id' => 3
        // ]);

        // User::create([
        //     'username' => 'dewi',
        //     'email' => 'dewi@mail.com',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('123456'),
        //     'remember_token' => Str::random(10),
        //     'picture' => 'admin.jpg',
        //     'is_admin' => true,
        //     'anggota_keluarga_id' => 4
        // ]);
    }
}
