<?php

namespace App\Models;

use CodeIgniter\Model;

class Replies extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'replies';
    protected $primaryKey       = 'REPLY_ID';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['PARENT_REPLY_ID', 'USER_ID', 'ID_JOB', 'REPLY', 'DATETIME_REPLIED'];

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

    public function getReply($id_reply)
    {
        $result = $this->select('login.PROFILE_PIC
                                ,login.USER
                                ,login.NAME
                                ,login.USER_ID
                                ,replies.REPLY_ID
                                ,replies.REPLY
                                ,replies.DATETIME_REPLIED')
            ->join('login', 'login.USER_ID = replies.USER_ID')
            ->where('replies.REPLY_ID', $id_reply)->get();

        return $result->getResultArray();
    }

    public function getJobReplies($id_job)
    {
        $result = $this->select('login.PROFILE_PIC              AS profile_pic
                                ,login.USER                     AS user
                                ,login.NAME                     AS name
                                ,login.USER_ID                  AS user_id
                                ,replies.REPLY_ID               AS comment_id
                                ,replies.REPLY                  AS comment
                                ,replies.DATETIME_REPLIED       AS comment_created')
            ->join('login', 'login.USER_ID = replies.USER_ID')
            ->where('replies.ID_JOB', $id_job)->orderBy('replies.DATETIME_REPLIED DESC')->get();

        return $result->getResultArray();
    }

    public function getReplyReplies($id_parent_reply)
    {
        $result = $this->select('login.PROFILE_PIC              AS profile_pic
                                ,login.USER                     AS user
                                ,login.NAME                     AS name
                                ,login.USER_ID                  AS user_id
                                ,replies.REPLY_ID               AS comment_id
                                ,replies.REPLY                  AS comment
                                ,replies.DATETIME_REPLIED       AS comment_created')
            ->join('login', 'login.USER_ID = replies.USER_ID')
            ->where('replies.PARENT_REPLY_ID', $id_parent_reply)->orderBy('replies.DATETIME_REPLIED DESC')->get();
        
        return $result->getResultArray();
    }

    public function countJobReplies($id_job)
    {
        $result = $this->select('REPLY_ID')->where('ID_JOB', $id_job)->countAllResults();

        return $result;
    }

    public function getRepliesOfThisReply($reply_id)
    {
        $result = $this->select('REPLY')->where('PARENT_REPLY_ID', $reply_id)->get()->getResultObject();

        return $result;
    }

    public function countRepliesOfThisReply($reply_id)
    {
        $result = $this->select('REPLY_ID')->where('PARENT_REPLY_ID', $reply_id)->countAllResults();

        return $result;
    }

    public function getRepliesDataAndPages($user_id, $currentPage = null)
    {
        if ($currentPage == null) {
            $result = $this->select('replies.REPLY_ID
                            ,replies.PARENT_REPLY_ID
                            ,replies.ID_JOB
                            ,replies.REPLY
                            ,replies.DATETIME_REPLIED')
                ->where('replies.USER_ID', $user_id)->countAllResults() / 10;
            return ceil($result);
        }
        $result = $this->select('replies.REPLY_ID
                                ,replies.PARENT_REPLY_ID
                                ,replies.ID_JOB
                                ,replies.REPLY
                                ,replies.DATETIME_REPLIED')
            ->where('replies.USER_ID', $user_id)->paginate(10, '', $currentPage);
        return $result;
    }
}
