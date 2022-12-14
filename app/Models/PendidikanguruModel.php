<?php

namespace App\Models;

use CodeIgniter\Model;

class PendidikanguruModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pendidikan_guru';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    static function view(){
        $view = (new PendidikanguruModel())
        ->select('pendidikan_guru.*, pegawai.nama_depan, pegawai.nama_belakang')
                ->join('pegawai', 'pegawai.id=pegawai_id')
                ->builder();

                $r = db_connect()->newQuery()->fromSubquery($view, 'tbl');
                $r->table = 'tbl';
                return $r;
    }
}
