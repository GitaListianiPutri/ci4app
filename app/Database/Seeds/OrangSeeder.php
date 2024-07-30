<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

use CodeIgniter\Database\Seeder;

class OrangSeeder extends Seeder
{
    // method untuk menjalankan/mengisi data ke dalam database
    public function run()
    {
        $data = [
            [
                'nama'      => 'Gita Listiani Putri',
                'alamat'    => 'Jl. Lingkar Selatan',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'nama'      => 'Fany Herly Wijaya',
                'alamat'    => 'Jl. Dago',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'nama'      => 'Vika Nuraini',
                'alamat'    => 'Jl. Geli',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ]
        ];

        // cara untuk menjalankan query nya
        // Simple Queries
        // $this->db->query('INSERT INTO Orang (nama, alamat, created_at, updated_at) VALUES(:nama:, :alamat:, :created_at:, :updated_at:)', $data);

        // lebih gampang
        // Using Query Builder
        // method insertBatchuntuk insert data sekali banyak
        // $this->db->table('Orang')->insert($data);
        $this->db->table('Orang')->insertBatch($data);
    }
}


// membuat seeder di terminal
// php spark make:seeder namaSeeder
// library faker untuk membuat data dalam jumlah yang banyak