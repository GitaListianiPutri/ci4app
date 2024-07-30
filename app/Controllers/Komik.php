<?php

namespace App\Controllers;

use Exception;

class Komik extends BaseController
{

    protected $komikModel;
    public function __construct()
    {
        $this->komikModel = new \App\Models\KomikModel();
    }
    public function index()
    {
        // $komik = $this->komikModel->findAll();
        $data = [
            'title' => 'Daftar komik',
            'komik' => $this->komikModel->getKomik()
        ];

        // $komikModel = new \App\Models\KomikModel();

        return view('/komik/index', $data);
    }

    public function detail($slug)
    {
        $komik = $this->komikModel->getKomik($slug);
        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];
        // jika komik tdk ada di table
        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Komik ' . $slug . ' tidak ditemukan.');
        }
        return view('komik/detail', $data);
    }

    public function create()
    {
        // session();
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation()
        ];

        return view('/komik/create', $data);
    }

    public function save()
    {
        // validasi input
        // jika judul yang diinputkan kosong dan sama, form tidak akan mengirimkan data trsbt
        if (!$this->validate([
            'judul' => [

                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ],
            // validasi sampul
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran file terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'

                ]
            ]
        ])) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->to('/create')->withInput();
        }


        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');
        // jika user tidak meng pload file
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.png';
        } else {

            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan file ke folder img
            $fileSampul->move('img', $namaSampul);
        }


        // membuat string menjadi huruf kecil semua dan spasinya hilang
        $slug = url_title($this->request->getVar('judul'), '-', true);
        // insert ke dalam database
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul

        ]);
        // flash data
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');

        // balik ke halaman komik index
        return redirect()->to('/komik');
    }

    // method delete
    public function delete($id)
    {
        // hapus gambar supaya di folder juga ikut kehapus
        // cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);


        // cek jika gambar default
        if ($komik['sampul'] != 'default.png') {
            // hapus gambar
            unlink('img/' . $komik['sampul']);
        }

        $this->komikModel->delete($id);
        // flas h data
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('/komik');
    }

    // method edit
    public function edit($slug)
    {
        $data = [
            'title' => 'Form Edit Data Komik',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('/komik/edit', $data);
    }

    public function update($id)
    {

        // cek judul, jika user tidak ganti judul maka tidak ada kondisi is_uniq
        // tapi jika user mengganti judulnya, dilakukan pengecekan apakah judul baru sudah ada di database/belum
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }

        // validasi input with multiple rules
        // jika judul yang diinputkan kosong dan sama, form tidak akan mengirimkan data trsbt
        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus diisi',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ],
            // validasi sampul
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran file terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'

                ]
            ]

        ])) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->to('/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        // cek gambar apakah tetap gambar lama
        if ($fileSampul->getError() == 4) {

            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate nama file random 
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan gambar/upload gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file yang lama
            if ($this->request->getVar('sampulLama') != 'default.png') {
                unlink('img/' . $this->request->getVar('sampulLama'));
            }
        }


        // membuat string menjadi huruf kecil semua dan spasinya hilang
        $slug = url_title($this->request->getVar('judul'), '-', true);
        // insert ke dalam database
        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul

        ]);
        // flash data
        session()->setFlashdata('pesan', 'Data berhasil diedit');

        // balik ke halaman komik index
        return redirect()->to('/komik');
    }
}
