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

    public function getJobLikes($id_job)
    {
        $result = $this->select('LIKE_ID')->where('ID_JOB', $id_job)->countAllResults();

        return $result;
    }

    public function checkUserLikedJob($id_job, $user_id)
    {
        $result = $this->select('LIKE_ID')->where('ID_JOB', $id_job)->where('USER_ID', $user_id)->countAllResults();

        return $result;
    }

    public function checkUserLiked($id_job, $user_id)
    {
        $result = $this->select('LIKE_ID')->where('ID_JOB', $id_job)->where('USER_ID', $user_id)->find();

        return $result;
    }
}
