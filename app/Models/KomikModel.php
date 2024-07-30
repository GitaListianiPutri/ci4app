<?php

namespace App\Models;

use CodeIgniter\Model;

class KomikModel extends Model
{
    // properti
    protected $table = 'komik';
    protected $dateFormat = 'datetime';
    protected $useTimestamps = true;
    protected $allowedFields = ['judul', 'slug', 'penulis', 'penerbit', 'sampul'];

    public function getKomik($slug = false)
    {
        if ($slug == false) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }
}
