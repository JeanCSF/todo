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
                'JOB_TITLE'         => isset($post['job_name']) ? $post['job_name'] : $post['header_job_name'] ,
                'JOB'               => isset($post['job_desc']) ? nl2br($post['job_desc']) : nl2br($post['header_job_desc']) ,
                'DATETIME_CREATED'  => date('Y-m-d H:i:s'),
                'USER_ID'           => $_SESSION['USER_ID'],
                'PRIVACY'           => (isset($post['privacy_select'])) ? $post['privacy_select'] : 1,
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
        $data = $this->select('jobs.ID_JOB, jobs.USER_ID, jobs.JOB_TITLE, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED, jobs.PRIVACY')
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.USER_ID', $id)
            ->orderBy('jobs.ID_JOB')->paginate(5);

        return $data;
    }

    public function getUserDoneJobs($id)
    {
        $data = $this->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.DATETIME_FINISHED !=', NULL)
            ->where('jobs.USER_ID', $id)->paginate(10);
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

    public function changeJobPrivacy($post)
    {
        date_default_timezone_set('America/Sao_Paulo');
        if (!empty($post)) {
            $data = [
                'DATETIME_UPDATED'  => date('Y-m-d H:i:s'),
                'PRIVACY'           => $post['privacyRb']
            ];
            return $this->table('jobs')->update($post['privacy_id'], $data) ? true : false;
        }
    }

    public function getJobsForProfile($id)
    {
        $data = $this->select('login.PROFILE_PIC
                                , login.USER
                                , login.USER_ID
                                , jobs.ID_JOB
                                , jobs.USER_ID
                                , jobs.JOB_TITLE
                                , jobs.JOB
                                , jobs.DATETIME_CREATED
                                , jobs.DATETIME_UPDATED
                                , jobs.DATETIME_FINISHED
                                , jobs.PRIVACY')
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.USER_ID', $id)
            ->where('jobs.PRIVACY', true)
            ->orderBy('jobs.DATETIME_CREATED DESC')->paginate(5);
        return $data;
    }

    public function getJobsDataAndPages($user_id, $currentPage = null)
    {
        if ($currentPage == null) {
            $result = $this->select('jobs.ID_JOB
                                    ,jobs.USER_ID
                                    ,jobs.JOB_TITLE
                                    ,jobs.JOB
                                    ,jobs.DATETIME_CREATED
                                    ,jobs.DATETIME_UPDATED
                                    ,jobs.DATETIME_FINISHED
                                    ,jobs.PRIVACY')
                ->select('COALESCE(likes.NUM_LIKES, 0) AS NUM_LIKES', false)
                ->select('COALESCE(replies.NUM_REPLIES, 0) AS NUM_REPLIES', false)
                ->join('(SELECT CONTENT_ID, COUNT(LIKE_ID) AS NUM_LIKES FROM likes GROUP BY CONTENT_ID) AS likes', 'likes.CONTENT_ID = jobs.ID_JOB', 'left')
                ->join('(SELECT ID_JOB, COUNT(REPLY_ID) AS NUM_REPLIES FROM replies GROUP BY ID_JOB) AS replies', 'replies.ID_JOB = jobs.ID_JOB', 'left')
                ->where('jobs.PRIVACY', true)->where('jobs.USER_ID', $user_id)->countAllResults() / 10;
            
            return ceil($result);
        }
        $result = $this->select('jobs.ID_JOB
                                ,jobs.USER_ID
                                ,jobs.JOB_TITLE
                                ,jobs.JOB
                                ,jobs.DATETIME_CREATED
                                ,jobs.DATETIME_UPDATED
                                ,jobs.DATETIME_FINISHED
                                ,jobs.PRIVACY')
            ->select('COALESCE(likes.NUM_LIKES, 0) AS NUM_LIKES', false)
            ->select('COALESCE(replies.NUM_REPLIES, 0) AS NUM_REPLIES', false)
            ->join('(SELECT CONTENT_ID, COUNT(LIKE_ID) AS NUM_LIKES FROM likes GROUP BY CONTENT_ID) AS likes', 'likes.CONTENT_ID = jobs.ID_JOB', 'left')
            ->join('(SELECT ID_JOB, COUNT(REPLY_ID) AS NUM_REPLIES FROM replies GROUP BY ID_JOB) AS replies', 'replies.ID_JOB = jobs.ID_JOB', 'left')
            ->where('jobs.PRIVACY', true)->where('jobs.USER_ID', $user_id)->orderBy('NUM_LIKES DESC, NUM_REPLIES DESC, jobs.DATETIME_CREATED DESC')->paginate(10, 'default', $currentPage);

        return $result;
    }
}
