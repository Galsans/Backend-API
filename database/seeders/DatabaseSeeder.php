<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Department::create([
            'name' => 'Pemberitaan',
            'kode' => 'RED',
        ]);
        Department::create([
            'name' => 'Artistik & Multimedia',
            'kode' => 'ARM',
        ]);
        Department::create([
            'name' => 'HR-GA & IT',
            'kode' => 'HGI',
        ]);
        Department::create([
            'name' => 'Keuangan',
            'kode' => 'KAP',
        ]);
        Department::create([
            'name' => 'Sales & Marketing',
            'kode' => 'SNM',
        ]);

        $this->call(DivisiSeeder::class);
        $this->call(CategorySeeder::class);

        // Data User
        User::create([
            'name' => 'Galsans',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'divisi_id' => 1,
            'role' => 'admin',
            'kode' => Str::upper(Str::random(5))
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'divisi_id' => 1,
            'role' => 'user',
            'kode' => Str::upper(Str::random(5))
        ]);
    }
}
