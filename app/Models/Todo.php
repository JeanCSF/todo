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
    protected $allowedFields    = ['JOB_TITLE', 'JOB', 'DATETIME_CREATED', 'DATETIME_UPDATED', 'DATETIME_FINISHED', 'USER_ID', 'PRIVACY'];



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
                'JOB_TITLE'         => $post['job_name'],
                'JOB'               => $post['job_desc'],
                'DATETIME_CREATED'  => date('Y-m-d H:i:s'),
                'USER_ID'           => $_SESSION['USER_ID'],
                'PRIVACY'           => 1,
            ];
            return $this->save($data) ? true : false;
        }
    }

    public function editJob($post)
    {
        date_default_timezone_set('America/Sao_Paulo');
        if (!empty($post)) {
            $data = [
                'JOB_TITLE'         => $post['job_name'],
                'JOB'               => $post['job_desc'],
                'DATETIME_UPDATED'  => date('Y-m-d H:i:s'),
            ];
            return $this->table('jobs')->update($post['id_job'], $data) ? true : false;
        }
    }

    public function finishJob($params)
    {
        return $this->query("UPDATE jobs SET DATETIME_FINISHED = NOW(), DATETIME_UPDATED = NOW() WHERE ID_JOB = :ID_JOB:", $params) ? true : false;
    }

    public function deleteJob($post)
    {
        if (!empty($post)) {

            return $this->table('jobs')->where('ID_JOB', $post['id'])->delete() ? true : false;
        }
    }

    public function getUserJobs($id)
    {
        $data = $this->select('jobs.ID_JOB, jobs.USER_ID, jobs.JOB_TITLE, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED')
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.USER_ID', $id)
            ->orderBy('jobs.ID_JOB')->paginate(5);

        return $data;
    }

    public function countAllUserJobs($id)
    {
        $data = $this->select('*')
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.USER_ID', $id)
            ->countAllResults();

        return $data;
    }

    public function countAllUserDoneJobs($id)
    {
        $data = $this->select()
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.USER_ID', $id)
            ->where('DATETIME_FINISHED !=', NULL)->countAllResults();

        return $data;
    }
    public function countAllUserNotDoneJobs($id)
    {
        $data = $this->select()
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.USER_ID', $id)
            ->where('DATETIME_FINISHED =', NULL)->countAllResults();

        return $data;
    }
}
