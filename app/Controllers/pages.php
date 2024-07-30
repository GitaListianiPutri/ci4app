<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    // method index
    {
        $data = [
            'title' => 'Home | Web Gita'
        ];
        // echo view('layout/header', $data);
        // apapun yang ada di dalam data terkirim ke dalam view
        return view('pages/home', $data);
        // echo view('layout/footer');
    }
    public function about()
    {
        $data = [
            'title' => 'About | Web Gita',
            'test' => ['satu', 'dua', 'tiga']
        ];
        // echo view('layout/header', $data);
        return view('pages/about', $data);
        // echo view('layout/footer');
    }

    public function contact()
    {
        $data = [
            'title' => 'contact us',
            'alamat' => [
                [
                'tipe' => 'rumah',
                'alamat' => 'Jl. Lingkar selatan',
                'kota' => 'cilacap'
            ],
            [
                'tipe' => 'Kantor',
                'alamat' => 'Jl. Budut',
                'kota' => 'Cilacap'
            ]
            ]
        ];

        return view('pages/contact', $data);
    }
}
