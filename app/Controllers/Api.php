<?php

namespace App\Controllers;

use CodeIgniter\Debug\Exceptions;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Api extends ResourceController
{
    private $mainController;
    private $jobsModel;
    private $likesModel;
    private $repliesModel;
    private $token = 'ihgfedcba987654321';

    public function __construct()
    {
        $this->mainController = new \App\Controllers\Main();
        $this->jobsModel = new \App\Models\Todo();
        $this->likesModel = new \App\Models\Likes();
        $this->repliesModel = new \App\Models\Replies();
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
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
            $currentPage = $this->request->getVar('page');
            $jobs = $this->jobsModel->select('login.PROFILE_PIC
                            ,login.USER
                            ,login.NAME
                            ,login.USER_ID
                            ,jobs.ID_JOB
                            ,jobs.USER_ID
                            ,jobs.JOB_TITLE
                            ,jobs.JOB
                            ,jobs.DATETIME_CREATED
                            ,jobs.DATETIME_UPDATED
                            ,jobs.DATETIME_FINISHED
                            ,jobs.PRIVACY')
                ->join('login', 'login.USER_ID = jobs.USER_ID')
                ->where('jobs.PRIVACY', true)->orderBy('jobs.ID_JOB ASC')->paginate(10, '', $currentPage);

            foreach ($jobs as $key => $job) {
                $response[] = [
                    'profile_pic'           => $job->PROFILE_PIC,
                    'user'                  => $job->USER,
                    'name'                  => $job->NAME,
                    'user_id'               => $job->USER_ID,
                    'job_id'                => $job->ID_JOB,
                    'job_title'             => $job->JOB_TITLE,
                    'job'                   => $job->JOB,
                    'job_created'           => isset($job->DATETIME_CREATED) ? date("d/m/Y", strtotime($job->DATETIME_CREATED)) : "",
                    'job_updated'           => isset($job->DATETIME_UPDATED) ? date("d/m/Y", strtotime($job->DATETIME_UPDATED)) : "",
                    'job_finished'          => isset($job->DATETIME_FINISHED) ? date("d/m/Y", strtotime($job->DATETIME_FINISHED)) : "",
                    'job_privacy'           => $job->PRIVACY,
                    'job_likes'             => $this->likesModel->getJobLikes($job->ID_JOB),
                    'job_num_comments'      => $this->repliesModel->countJobReplies($job->ID_JOB),
                    'user_liked'            => $this->likesModel->checkUserLikedJob($job->ID_JOB, $this->session->USER_ID),

                ];
            }

            return $this->respond($response);
        } else {
            $this->mainController->main();
        }
    }

    public function likeJob()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        if ($this->_tokenValidate()) {
            $newLike = [
                'LIKE_ID'           =>  $this->session->USER . date("Y-m-d H:i:s"),
                'USER_ID'           =>  $this->request->getPost('user_id'),
                'CONTENT_ID'        =>  $this->request->getPost('job_id'),
                'TYPE'              =>  'POST',
                'DATETIME_LIKED'    =>  date("Y-m-d H:i:s"),
            ];

            $checkLike = $this->likesModel->getInfoIfAlreadyLikedJob($newLike['CONTENT_ID'], $newLike['USER_ID']);

            try {
                if (!empty($checkLike)) {
                    $this->likesModel->where('LIKE_ID', $checkLike[0]->LIKE_ID)->delete();
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Unliked Post'
                    ];
                } else {
                    $this->likesModel->save($newLike);
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Liked Post'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro ao dar like no Post',
                    'errors'    =>  [
                        'exception' =>  $e->getMessage()
                    ],
                ];
            }
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
        return $this->response->setJson($response);
    }

    public function commentJob()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        if ($this->_tokenValidate()) {
            $reply = $this->request->getPost('reply');
            $newComment = [];
            if ($reply == 1) {
                $newComment = [
                    'USER_ID'               =>  $this->request->getPost('user_id'),
                    'PARENT_REPLY_ID'       =>  $this->request->getPost('reply_id'),
                    'ID_JOB'                =>  $this->request->getPost('job_id'),
                    'REPLY'                 =>  $this->request->getPost('reply_comment'),
                    'DATETIME_REPLIED'      =>  date("Y-m-d H:i:s"),
                ];
            }

            if ($reply == 0) {
                $newComment = [
                    'USER_ID'               =>  $this->request->getPost('user_id'),
                    'ID_JOB'                =>  $this->request->getPost('job_id'),
                    'REPLY'                 =>  $this->request->getPost('job_comment'),
                    'DATETIME_REPLIED'      =>  date("Y-m-d H:i:s"),
                ];
            }

            try {
                if (!empty($newComment)) {
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Commented Post'
                    ];
                    $this->repliesModel->save($newComment);
                } else {
                    $response = [
                        'response'  =>  'error',
                        'msg'       =>  'Erro ao comentar no post'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro inesperado',
                    'errors'    =>  [
                        'exception' =>  $e->getMessage()
                    ],
                ];
            }
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
        return $this->response->setJson($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        if (isset($this->session->USER_ID)) {
            if ($this->_tokenValidate() && $id != null) {
                $response = [];

                $jobs = $this->jobsModel->select('login.PROFILE_PIC
                            ,login.USER
                            ,login.NAME
                            ,login.USER_ID
                            ,jobs.ID_JOB
                            ,jobs.USER_ID
                            ,jobs.JOB_TITLE
                            ,jobs.JOB
                            ,jobs.DATETIME_CREATED
                            ,jobs.DATETIME_UPDATED
                            ,jobs.DATETIME_FINISHED
                            ,jobs.PRIVACY')
                    ->join('login', 'login.USER_ID = jobs.USER_ID')
                    ->where('jobs.PRIVACY', true)->where('jobs.ID_JOB', $id)->get()->getResultArray();

                $comments = $this->repliesModel->select('login.PROFILE_PIC              AS profile_pic
                                                        ,login.USER                     AS user
                                                        ,login.NAME                     AS name
                                                        ,login.USER_ID                  AS user_id
                                                        ,replies.REPLY_ID               AS comment_id
                                                        ,replies.REPLY                  AS comment
                                                        ,replies.DATETIME_REPLIED       AS comment_created')
                    ->join('login', 'login.USER_ID = replies.USER_ID')
                    ->where('replies.ID_JOB', $id)->orderBy('replies.DATETIME_REPLIED DESC')->get()->getResultArray();

                try {
                    if (!empty($jobs)) {
                        $comment_info = [];
                        foreach ($jobs as $job) {
                            $job_info = [
                                'profile_pic'           => $job['PROFILE_PIC'],
                                'user'                  => $job['USER'],
                                'name'                  => $job['NAME'],
                                'user_id'               => $job['USER_ID'],
                                'job_id'                => $job['ID_JOB'],
                                'job_title'             => $job['JOB_TITLE'],
                                'job'                   => $job['JOB'],
                                'job_created'           => isset($job['DATETIME_CREATED']) ? date("d/m/Y", strtotime($job['DATETIME_CREATED'])) : "",
                                'job_updated'           => isset($job['DATETIME_UPDATED']) ? date("d/m/Y", strtotime($job['DATETIME_UPDATED'])) : "",
                                'job_finished'          => isset($job['DATETIME_FINISHED']) ? date("d/m/Y", strtotime($job['DATETIME_FINISHED'])) : "",
                                'job_privacy'           => $job['PRIVACY'],
                                'job_likes'             => $this->likesModel->getJobLikes($job['ID_JOB']),
                                'job_num_comments'      => $this->repliesModel->countJobReplies($job['ID_JOB']),
                                'user_liked'            => $this->likesModel->checkUserLikedJob($job['ID_JOB'], $this->session->USER_ID),
                            ];
                        }
                        foreach ($comments as $key => $comment) {
                            $comment_info[] = [
                                'profile_pic'           => $comment['profile_pic'],
                                'user'                  => $comment['user'],
                                'name'                  => $comment['name'],
                                'user_id'               => $comment['user_id'],
                                'comment_id'            => $comment['comment_id'],
                                'comment'               => $comment['comment'],
                                'comment_created'       => isset($comment['comment_created']) ? date("d/m/Y H:i:s", strtotime($comment['comment_created'])) : "",
                                'comment_likes'         => $this->likesModel->getReplyLikes($comment['comment_id']),
                                'comment_num_comments'  => $this->repliesModel->countRepliesOfThisReply($comment['comment_id']),
                                'user_liked'            => $this->likesModel->checkUserLikedReply($comment['comment_id'], $this->session->USER_ID),
                            ];
                        }
                        $response = [
                            'job'           =>  $job_info,
                            'job_comments'  =>  $comment_info
                        ];
                    } else {
                        $response = [
                            'response'  =>  'error',
                            'msg'       =>  'Post não encontrado'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'response'  =>  'error',
                        'msg'       =>  'Erro ao consultar post',
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
        } else {

            $this->mainController->main();
        }
    }

    public function likeComment()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        if ($this->_tokenValidate()) {
            $newLike = [
                'LIKE_ID'           =>  $this->session->USER . date("Y-m-d H:i:s"),
                'USER_ID'           =>  $this->request->getPost('user_id'),
                'CONTENT_ID'        =>  $this->request->getPost('comment_id'),
                'TYPE'              =>  'REPLY',
                'DATETIME_LIKED'    =>  date("Y-m-d H:i:s"),
            ];

            $checkLike = $this->likesModel->getInfoIfAlreadyLikedReply($newLike['CONTENT_ID'], $newLike['USER_ID']);

            try {
                if (!empty($checkLike)) {
                    $this->likesModel->where('LIKE_ID', $checkLike[0]->LIKE_ID)->delete();
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Unliked Post'
                    ];
                } else {
                    $this->likesModel->save($newLike);
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Liked Post'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro ao dar like no Post',
                    'errors'    =>  [
                        'exception' =>  $e->getMessage()
                    ],
                ];
            }
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
        return $this->response->setJson($response);
    }

    public function showComment($id = null)
    {
        if (isset($this->session->USER_ID)) {
            if ($this->_tokenValidate() && $id != null) {
                $response = [];

                $replies = $this->repliesModel->select('login.PROFILE_PIC
                            ,login.USER
                            ,login.NAME
                            ,login.USER_ID
                            ,replies.REPLY_ID
                            ,replies.REPLY
                            ,replies.DATETIME_REPLIED')
                    ->join('login', 'login.USER_ID = replies.USER_ID')
                    ->where('replies.REPLY_ID', $id)->get()->getResultArray();

                $comments = $this->repliesModel->select('login.PROFILE_PIC              AS profile_pic
                                                        ,login.USER                     AS user
                                                        ,login.NAME                     AS name
                                                        ,login.USER_ID                  AS user_id
                                                        ,replies.REPLY_ID               AS comment_id
                                                        ,replies.REPLY                  AS comment
                                                        ,replies.DATETIME_REPLIED       AS comment_created')
                    ->join('login', 'login.USER_ID = replies.USER_ID')
                    ->where('replies.PARENT_REPLY_ID', $id)->orderBy('replies.DATETIME_REPLIED DESC')->get()->getResultArray();

                try {
                    if (!empty($replies)) {
                        $comment_info = [];
                        foreach ($replies as $reply) {
                            $reply_info = [
                                'profile_pic'           => $reply['PROFILE_PIC'],
                                'user'                  => $reply['USER'],
                                'name'                  => $reply['NAME'],
                                'user_id'               => $reply['USER_ID'],
                                'reply_id'              => $reply['REPLY_ID'],
                                'reply'                 => $reply['REPLY'],
                                'reply_created'         => isset($reply['DATETIME_REPLIED']) ? date("d/m/Y H:i:s", strtotime($reply['DATETIME_REPLIED'])) : "",
                                'reply_likes'           => $this->likesModel->getReplyLikes($reply['REPLY_ID']),
                                'reply_num_comments'    => $this->repliesModel->countRepliesOfThisReply($reply['REPLY_ID']),
                                'user_liked'            => $this->likesModel->checkUserLikedReply($reply['REPLY_ID'], $this->session->USER_ID),
                            ];
                        }

                        foreach ($comments as $key => $comment) {
                            $comment_info[] = [
                                'profile_pic'           => $comment['profile_pic'],
                                'user'                  => $comment['user'],
                                'name'                  => $comment['name'],
                                'user_id'               => $comment['user_id'],
                                'comment_id'            => $comment['comment_id'],
                                'comment'               => $comment['comment'],
                                'comment_created'       => isset($comment['comment_created']) ? date("d/m/Y H:i:s", strtotime($comment['comment_created'])) : "",
                                'comment_likes'         => $this->likesModel->getReplyLikes($comment['comment_id']),
                                'comment_num_comments'  => $this->repliesModel->countRepliesOfThisReply($comment['comment_id']),
                                'user_liked'            => $this->likesModel->checkUserLikedReply($comment['comment_id'], $this->session->USER_ID),
                            ];
                        }
                        $response = [
                            'reply'             =>    $reply_info,
                            'reply_comments'    =>    $comment_info
                        ];
                    } else {
                        $response = [
                            'response'  =>  'error',
                            'msg'       =>  'Post não encontrado'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'response'  =>  'error',
                        'msg'       =>  'Erro ao consultar post',
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
        } else {

            $this->mainController->main();
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
