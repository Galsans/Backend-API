<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Departmet 1
        Divisi::create([
            'name' => 'Pemberitaan',
            'department_id' => 1,
        ]);
        Divisi::create([
            'name' => 'Sekred',
            'department_id' => 1,
        ]);
        Divisi::create([
            'name' => 'Percetakan',
            'department_id' => 1,
        ]);
        Divisi::create([
            'name' => 'Litbang',
            'department_id' => 1,
        ]);
        Divisi::create([
            'name' => 'Bahasa',
            'department_id' => 1,
        ]);
        Divisi::create([
            'name' => 'Sosial Media',
            'department_id' => 1,
        ]);
        Divisi::create([
            'name' => 'Mediaindonesia.com',
            'department_id' => 1,
        ]);


        // Data Department 2
        Divisi::create([
            'name' => 'Foto',
            'department_id' => 2,
        ]);
        Divisi::create([
            'name' => 'Artistik',
            'department_id' => 2,
        ]);
        Divisi::create([
            'name' => 'Multimedia',
            'department_id' => 2,
        ]);

        // Data Department 3
        Divisi::create([
            'name' => 'HR',
            'department_id' => 3,
        ]);
        Divisi::create([
            'name' => 'GA',
            'department_id' => 3,
        ]);
        Divisi::create([
            'name' => 'IT',
            'department_id' => 3,
        ]);

        // Data Department 4
        Divisi::create([
            'name' => 'Keuangan',
            'department_id' => 4,
        ]);
        Divisi::create([
            'name' => 'Akunting & Pajak',
            'department_id' => 4,
        ]);
        Divisi::create([
            'name' => 'Pusrchasing',
            'department_id' => 4,
        ]);

        // Data Department 5
        Divisi::create([
            'name' => 'Sales',
            'department_id' => 5,
        ]);
        Divisi::create([
            'name' => 'Akunting & Distribusi',
            'department_id' => 5,
        ]);
        Divisi::create([
            'name' => 'Promo',
            'department_id' => 5,
        ]);
        Divisi::create([
            'name' => 'Marketing Support',
            'department_id' => 5,
        ]);
        Divisi::create([
            'name' => 'Admin Support',
            'department_id' => 5,
        ]);
    }
}
