<?php

namespace App\Models;

use CodeIgniter\Model;

class Comments extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'comments';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['COMMENT_ID', 'USER_ID', 'ID_JOB', 'COMMENT', 'DATETIME_COMMENTED'];

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

    public function getJobComments($id_job)
    {
        $result = $this->select('COMMENT')->where('ID_JOB', $id_job)->get()->getResultObject();

        return $result;
    }

    public function countJobComments($id_job)
    {
        $result = $this->select('COMMENT_ID')->where('ID_JOB', $id_job)->countAllResults();

        return $result;
    }

}
