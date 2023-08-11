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
    protected $allowedFields    = ['LIKE_ID', 'USER_ID', 'CONTENT_ID', 'DATETIME_LIKED', 'TYPE'];



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

    public function checkUserLikedJob($id_job, $user_id)
    {
        $result = $this->select('LIKE_ID')->where('CONTENT_ID', $id_job)->where('USER_ID', $user_id)->where("TYPE = 'POST' ")->countAllResults();

        return $result;
    }

    public function getInfoIfAlreadyLikedJob($id_job, $user_id)
    {
        $result = $this->select('LIKE_ID')->where('CONTENT_ID', $id_job)->where('USER_ID', $user_id)->where("TYPE = 'POST' ")->find();

        return $result;
    }

    public function getContentLikes($content_id, $type)
    {
        if ($type == 'REPLY') {
            $result = $this->select('LIKE_ID')->where('CONTENT_ID', $content_id)->where("TYPE = 'REPLY' ")->countAllResults();

            return $result;
        }

        $result = $this->select('LIKE_ID')->where('CONTENT_ID', $content_id)->where("TYPE = 'POST' ")->countAllResults();

        return $result;
    }

    public function checkUserLikedReply($reply_id, $user_id)
    {
        $result = $this->select('LIKE_ID')->where('CONTENT_ID', $reply_id)->where('USER_ID', $user_id)->where("TYPE = 'REPLY' ")->countAllResults();

        return $result;
    }

    public function getInfoIfAlreadyLikedReply($reply_id, $user_id)
    {
        $result = $this->select('LIKE_ID')->where('CONTENT_ID', $reply_id)->where('USER_ID', $user_id)->where("TYPE = 'REPLY' ")->find();

        return $result;
    }

    public function getLikesDataAndPages($user_id, $currentPage = null)
    {
        if ($currentPage == null) {
            $result = $this->select('likes.LIKE_ID
                                ,likes.CONTENT_ID
                                ,likes.DATETIME_LIKED
                                ,likes.TYPE')->where('likes.USER_ID', $user_id)->countAllResults() / 10;

            return ceil($result);
        }
        $result = $this->select('likes.LIKE_ID
                                ,likes.CONTENT_ID
                                ,likes.DATETIME_LIKED
                                ,likes.TYPE')->where('likes.USER_ID', $user_id)->paginate(10, '', $currentPage);

        return $result;
    }
}
