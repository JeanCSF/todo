<?php

namespace App\Models;

use CodeIgniter\Model;

class Likes extends Model
{
    protected $table            = 'likes';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['LIKE_ID', 'USER_ID', 'ID_JOB', 'DATETIME_LIKED'];



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

    public function newLikeJob($id_job)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $checkLike = $this->select('LIKE_ID')->where("ID_JOB = {$id_job} AND USER_ID = {$_SESSION['USER_ID']}")->find();
        // dd($checkLike);
        if (empty($checkLike)) {
            $data = [
                'LIKE_ID'           => $_SESSION['USER'] . "_" . date("Y-m-d H:i:s"),
                'USER_ID'           => $_SESSION['USER_ID'],
                'ID_JOB'            => $id_job,
                'DATETIME_LIKED'    => date("Y-m-d H:i:s"),
            ];
            return $this->save($data) ? true : false;
        }
        if (!empty($checkLike)) {
            return $this->where('LIKE_ID',$checkLike[0]->LIKE_ID)->delete() ? true : false;
        }
    }

}
