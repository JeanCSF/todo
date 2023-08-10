<?php

namespace App\Controllers;

use CodeIgniter\Debug\Exceptions;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Api_users extends ResourceController
{
    private $mainController;
    private $jobsModel;
    private $likesModel;
    private $repliesModel;
    private $usersModel;
    private $token = 'ihgfedcba987654321';

    public function __construct()
    {
        $this->mainController = new \App\Controllers\Main();
        $this->jobsModel = new \App\Models\Todo();
        $this->likesModel = new \App\Models\Likes();
        $this->repliesModel = new \App\Models\Replies();
        $this->usersModel = new \App\Models\Users();
        $this->session = \Config\Services::session();
    }

    private function _tokenValidate()
    {
        return $this->request->getHeaderLine('token') == $this->token;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($user = null)
    {
        if ($user != null) {
            $currentPage = $this->request->getVar('page') ?? 1;

            $response = [];
            $user_jobs = [];
            $userInfo = $this->usersModel->where('USER', $user)->get()->getRow();
            $userJobs = $this->jobsModel->select('jobs.ID_JOB
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
                ->where('jobs.PRIVACY', true)->where('jobs.USER_ID', $userInfo->USER_ID)->orderBy('NUM_LIKES DESC, NUM_REPLIES DESC, jobs.DATETIME_CREATED DESC')->paginate(10, 'default', $currentPage);

            $pages = $this->jobsModel->select('jobs.ID_JOB
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
                ->where('jobs.PRIVACY', true)->where('jobs.USER_ID', $userInfo->USER_ID)->countAllResults() / 10;

            try {
                if ($currentPage <= round($pages)) {
                    $user_info = [
                        'profile_pic'           => $userInfo->PROFILE_PIC,
                        'user'                  => $userInfo->USER,
                        'name'                  => $userInfo->NAME,
                        'user_id'               => $userInfo->USER_ID,
                    ];

                    foreach ($userJobs as $job) {
                        $user_jobs[] = [
                            'job_id'                => $job->ID_JOB,
                            'job_title'             => $job->JOB_TITLE,
                            'job'                   => $job->JOB,
                            'job_created'           => isset($job->DATETIME_CREATED) ? date("d/m/Y", strtotime($job->DATETIME_CREATED)) : "",
                            'job_updated'           => isset($job->DATETIME_UPDATED) ? date("d/m/Y", strtotime($job->DATETIME_UPDATED)) : "",
                            'job_finished'          => isset($job->DATETIME_FINISHED) ? date("d/m/Y", strtotime($job->DATETIME_FINISHED)) : "",
                            'job_privacy'           => $job->PRIVACY,
                            'job_likes'             => $job->NUM_LIKES,
                            'job_num_comments'      => $job->NUM_REPLIES,
                            'user_liked'            => $this->likesModel->checkUserLikedJob($job->ID_JOB, $this->session->USER_ID),
                        ];
                    }

                    $response = [
                        'user_info'     =>  $user_info,
                        'user_jobs'     =>  $user_jobs,
                    ];
                } else {
                    $response = [];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro ao consultar usuário',
                    'errors'    =>  [
                        'exception' =>  $e->getMessage()
                    ],
                ];
            }

            return $this->respond($response);
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
    }

    public function getReplies($user_id = null)
    {
        if ($user_id != null) {
            $currentPage = $this->request->getVar('page');
            $response = [];
            $user_replies = [];
            $userInfo = $this->usersModel->where('USER_ID', $user_id)->get()->getRow();
            $replies = $this->repliesModel->select('replies.REPLY_ID
                            ,replies.PARENT_REPLY_ID
                            ,replies.ID_JOB
                            ,replies.REPLY
                            ,replies.DATETIME_REPLIED')
                ->where('replies.USER_ID', $user_id)->paginate(10, '', $currentPage);
            $pages = $this->repliesModel->select('replies.REPLY_ID
                                                ,replies.PARENT_REPLY_ID
                                                ,replies.ID_JOB
                                                ,replies.REPLY
                                                ,replies.DATETIME_REPLIED')
                ->where('replies.USER_ID', $user_id)->countAllResults() / 10;
            try {
                if ($currentPage <= round($pages)) {
                    foreach ($replies as $reply) {
                        $user_replies[] = [
                            'reply_id'              => $reply->REPLY_ID,
                            'parent_reply_id'       => $reply->PARENT_REPLY_ID,
                            'reply_id_job'          => $reply->ID_JOB,
                            'reply'                 => $reply->REPLY,
                            'datetime_replied'      => isset($reply->DATETIME_REPLIED) ? date("d/m/Y H:i:s", strtotime($reply->DATETIME_REPLIED)) : "",
                            'reply_likes'           => $this->likesModel->getReplyLikes($reply->REPLY_ID),
                            'reply_num_comments'    => $this->repliesModel->countRepliesOfThisReply($reply->REPLY_ID),
                            'user_liked'            => $this->likesModel->checkUserLikedReply($reply->REPLY_ID, $this->session->USER_ID),
                        ];
                    }

                    $response = [
                        'replies'       =>  $user_replies
                    ];
                } else {
                    $response = [];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro ao consultar usuário',
                    'errors'    =>  [
                        'exception' =>  $e->getMessage()
                    ],
                ];
            }

            return $this->respond($response);
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
    }

    public function getLikes($user_id = null)
    {
        if ($user_id != null) {
            $currentPage = $this->request->getVar('page');
            $response = [];
            $user_replies = [];
            $userInfo = $this->usersModel->where('USER_ID', $user_id)->get()->getRow();
            $replies = $this->repliesModel->select('replies.REPLY_ID
                            ,replies.PARENT_REPLY_ID
                            ,replies.ID_JOB
                            ,replies.REPLY
                            ,replies.DATETIME_REPLIED')
                ->where('replies.USER_ID', $user_id)->paginate(10, '', $currentPage);
            $pages = $this->repliesModel->select('replies.REPLY_ID
                                                ,replies.PARENT_REPLY_ID
                                                ,replies.ID_JOB
                                                ,replies.REPLY
                                                ,replies.DATETIME_REPLIED')
                ->where('replies.USER_ID', $user_id)->countAllResults() / 10;
            try {
                if ($currentPage <= round($pages)) {
                    foreach ($replies as $reply) {
                        $user_replies[] = [
                            'reply_id'              => $reply->REPLY_ID,
                            'parent_reply_id'       => $reply->PARENT_REPLY_ID,
                            'reply_id_job'          => $reply->ID_JOB,
                            'reply'                 => $reply->REPLY,
                            'datetime_replied'      => isset($reply->DATETIME_REPLIED) ? date("d/m/Y H:i:s", strtotime($reply->DATETIME_REPLIED)) : "",
                            'reply_likes'           => $this->likesModel->getReplyLikes($reply->REPLY_ID),
                            'reply_num_comments'    => $this->repliesModel->countRepliesOfThisReply($reply->REPLY_ID),
                            'user_liked'            => $this->likesModel->checkUserLikedReply($reply->REPLY_ID, $this->session->USER_ID),
                        ];
                    }

                    $response = [
                        'replies'       =>  $user_replies
                    ];
                } else {
                    $response = [];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro ao consultar usuário',
                    'errors'    =>  [
                        'exception' =>  $e->getMessage()
                    ],
                ];
            }

            return $this->respond($response);
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
