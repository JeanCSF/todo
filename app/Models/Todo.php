<?php

namespace App\Models;

use CodeIgniter\Model;

class Todo extends Model
{
    protected $table            = 'jobs';
    protected $primaryKey       = 'ID_JOB';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['JOB', 'DATETIME_CREATED', 'DATETIME_UPDATED', 'DATETIME_FINISHED', 'USER_ID'];



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

    public function getAll()
    {
        $result = $this->orderBy('ID_JOB')->findAll();
        return $result;
    }

    public function getJob($id_job)
    {
        $result = $this->find($id_job);
        return $result;
    }

    public function insertJob($post)
    {
        date_default_timezone_set('America/Sao_Paulo');

        if (!empty($post)) {
            $data = [
                'JOB'               => $post['job_name'],
                'DATETIME_CREATED'  => date('Y-m-d H:i:s'),
                'USER_ID'           => $_SESSION['USER_ID']
            ];
            return $this->save($data) ? true : false;
        }
    }

    public function editJob($params)
    {
        return $this->query("UPDATE jobs SET DATETIME_UPDATED = NOW(), JOB = :JOB: WHERE ID_JOB = :ID_JOB:", $params) ? true : false;
    }

    public function finishJob($params)
    {
        return $this->query("UPDATE jobs SET DATETIME_FINISHED = NOW(), DATETIME_UPDATED = NOW() WHERE ID_JOB = :ID_JOB:", $params) ? true : false;
    }

    public function deleteJob($params)
    {
        return $this->query("DELETE FROM jobs WHERE ID_JOB = :ID_JOB:", $params)? true : false;
    }
}
