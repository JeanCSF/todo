<?php

namespace App\Controllers;

use App\Libraries\HTMLPurifierService;
use App\Libraries\TimeElapsedStringService;
use CodeIgniter\Debug\Exceptions;
use CodeIgniter\RESTful\ResourceController;
use Exception;

use function PHPSTORM_META\type;

class Api_jobs extends ResourceController
{
    private $HTMLPurifier;
    private $TimeElapsedString;

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
        $this->HTMLPurifier = new HTMLPurifierService();
        $this->TimeElapsedString = new TimeElapsedStringService();
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
            $currentPage = $this->request->getVar('page') ?? 1;
            $jobs = $this->jobsModel->getIndexDataAndPages($currentPage);
            $pages = $this->jobsModel->getIndexDataAndPages();

            foreach ($jobs as $key => $job) {
                if ($currentPage <= $pages) {
                    $response[] = [
                        'profile_pic'           => $job->PROFILE_PIC,
                        'user'                  => $job->USER,
                        'name'                  => $job->NAME,
                        'user_id'               => $job->USER_ID,
                        'job_id'                => $job->ID_JOB,
                        'job_title'             => $this->HTMLPurifier->html_purify($job->JOB_TITLE),
                        'job'                   => $this->HTMLPurifier->html_purify($job->JOB),
                        'job_created'           => isset($job->DATETIME_CREATED) ? $this->TimeElapsedString->time_elapsed_string($job->DATETIME_CREATED) : "",
                        'job_updated'           => isset($job->DATETIME_UPDATED) ? 'Atualizado: ' . $this->TimeElapsedString->time_elapsed_string($job->DATETIME_UPDATED) : "",
                        'job_finished'          => isset($job->DATETIME_FINISHED) ? 'Finalizado: ' . $this->TimeElapsedString->time_elapsed_string($job->DATETIME_FINISHED) : "",
                        'job_privacy'           => $job->PRIVACY,
                        'job_likes'             => $job->NUM_LIKES,
                        'job_num_comments'      => $job->NUM_REPLIES,
                        'user_liked'            => $this->likesModel->checkUserLikedJob($job->ID_JOB, $this->session->USER_ID),
                        'type'                  => 'POST'

                    ];
                } else {
                    $response = [];
                }
            }

            return $this->respond($response);
        } else {
            $this->mainController->main();
        }
    }

    public function likeContent()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $requestInfo = $this->request->getJSON();

        $response = [];
        if ($this->_tokenValidate()) {
            $newLike = [
                'LIKE_ID'           =>  $this->session->USER . '_' . date("Y_m_d_H_i_s"),
                'USER_ID'           =>  $requestInfo->user_id,
                'CONTENT_ID'        =>  $requestInfo->content_id,
                'TYPE'              =>  $requestInfo->type_content,
                'DATETIME_LIKED'    =>  date("Y-m-d H:i:s"),
            ];

            $checkLike = $this->likesModel->getInfoIfAlreadyLikedContent($newLike['CONTENT_ID'], $newLike['USER_ID'], $newLike['TYPE']);

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
                        'exception' =>  $e->getMessage(),
                    ],

                ];
            }
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
        return $this->respond($response);
    }

    public function commentContent()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        $requestInfo = $this->request->getJSON();
        if ($this->_tokenValidate()) {
            $newComment = [];
            if ($requestInfo->type_content == 'REPLY') {
                $newComment = [
                    'USER_ID'               =>  $requestInfo->user_id,
                    'PARENT_REPLY_ID'       =>  $requestInfo->content_id,
                    'REPLY'                 =>  $requestInfo->comment,
                    'DATETIME_REPLIED'      =>  date("Y-m-d H:i:s"),
                ];
            }

            if ($requestInfo->type_content == 'POST') {
                $newComment = [
                    'USER_ID'               =>  $requestInfo->user_id,
                    'ID_JOB'                =>  $requestInfo->content_id,
                    'REPLY'                 =>  $requestInfo->comment,
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
        return $this->respond($response);
    }

    public function show($id = null)
    {
        if (isset($this->session->USER_ID)) {
            if ($id != null) {
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
                    ->select('COALESCE(likes.NUM_LIKES, 0) AS NUM_LIKES', false)
                    ->select('COALESCE(replies.NUM_REPLIES, 0) AS NUM_REPLIES', false)
                    ->join('login', 'login.USER_ID = jobs.USER_ID')
                    ->join('(SELECT CONTENT_ID, TYPE, COUNT(LIKE_ID) AS NUM_LIKES FROM likes GROUP BY CONTENT_ID) AS likes', "likes.CONTENT_ID = jobs.ID_JOB AND likes.TYPE = 'POST'", 'left')
                    ->join('(SELECT ID_JOB, COUNT(REPLY_ID) AS NUM_REPLIES FROM replies GROUP BY ID_JOB) AS replies', 'replies.ID_JOB = jobs.ID_JOB', 'left')
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
                                'job_title'             => $this->HTMLPurifier->html_purify($job['JOB_TITLE']),
                                'job'                   => $this->HTMLPurifier->html_purify($job['JOB']),
                                'job_created'           => isset($job['DATETIME_CREATED']) ? $this->TimeElapsedString->time_elapsed_string($job['DATETIME_CREATED']) : "",
                                'job_updated'           => isset($job['DATETIME_UPDATED']) ? 'Atualizado: ' . $this->TimeElapsedString->time_elapsed_string($job['DATETIME_UPDATED']) : "",
                                'job_finished'          => isset($job['DATETIME_FINISHED']) ? 'Finalizado: ' . $this->TimeElapsedString->time_elapsed_string($job['DATETIME_FINISHED']) : "",
                                'job_privacy'           => $job['PRIVACY'],
                                'job_likes'             => $job['NUM_LIKES'],
                                'job_num_comments'      => $job['NUM_REPLIES'],
                                'user_liked'            => $this->likesModel->checkUserLikedJob($job['ID_JOB'], $this->session->USER_ID),
                                'type'                  => 'POST'
                            ];
                        }
                        foreach ($comments as $key => $comment) {
                            $comment_info[] = [
                                'profile_pic'           => $comment['profile_pic'],
                                'user'                  => $comment['user'],
                                'name'                  => $comment['name'],
                                'user_id'               => $comment['user_id'],
                                'reply_id'              => $comment['comment_id'],
                                'reply'                 => $this->HTMLPurifier->html_purify($comment['comment']),
                                'datetime_replied'      => isset($comment['comment_created']) ? $this->TimeElapsedString->time_elapsed_string($comment['comment_created']) : "",
                                'reply_likes'           => $this->likesModel->getContentLikes($comment['comment_id'], 'REPLY'),
                                'reply_num_comments'    => $this->repliesModel->countRepliesOfThisReply($comment['comment_id']),
                                'user_liked'            => $this->likesModel->checkUserLikedReply($comment['comment_id'], $this->session->USER_ID),
                                'type'                  => 'REPLY',
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
                                'reply'                 => $this->HTMLPurifier->html_purify($reply['REPLY']),
                                'reply_created'         => isset($reply['DATETIME_REPLIED']) ? $this->TimeElapsedString->time_elapsed_string($reply['DATETIME_REPLIED']) : "",
                                'reply_likes'           => $this->likesModel->getContentLikes($reply['REPLY_ID'], 'REPLY'),
                                'reply_num_comments'    => $this->repliesModel->countRepliesOfThisReply($reply['REPLY_ID']),
                                'user_liked'            => $this->likesModel->checkUserLikedReply($reply['REPLY_ID'], $this->session->USER_ID),
                                'type'                  => 'REPLY'
                            ];
                        }

                        foreach ($comments as $key => $comment) {
                            $comment_info[] = [
                                'profile_pic'           => $comment['profile_pic'],
                                'user'                  => $comment['user'],
                                'name'                  => $comment['name'],
                                'user_id'               => $comment['user_id'],
                                'reply_id'              => $comment['comment_id'],
                                'reply'                 => $this->HTMLPurifier->html_purify($comment['comment']),
                                'datetime_replied'      => isset($comment['comment_created']) ? $this->TimeElapsedString->time_elapsed_string($comment['comment_created']) : "",
                                'reply_likes'         => $this->likesModel->getContentLikes($comment['comment_id'], 'REPLY'),
                                'reply_num_comments'  => $this->repliesModel->countRepliesOfThisReply($comment['comment_id']),
                                'user_liked'            => $this->likesModel->checkUserLikedReply($comment['comment_id'], $this->session->USER_ID),
                                'type'                  => 'REPLY'
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


    public function new()
    {
        //
    }

    public function create()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        $requestInfo = $this->request->getJSON();
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
                $newJob = [
                    'USER_ID'               =>  $requestInfo->user_id,
                    'JOB_TITLE'             =>  $requestInfo->job_title,
                    'JOB'                   =>  $requestInfo->job,
                    'DATETIME_CREATED'      =>  date("Y-m-d H:i:s"),
                    'PRIVACY'               =>  $requestInfo->job_privacy,
                ];
            try {
                if (!empty($newJob)) {
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Job Created'
                    ];
                    $this->jobsModel->save($newJob);
                } else {
                    $response = [
                        'response'  =>  'error',
                        'msg'       =>  'Job Create Error'
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
        return $this->respond($response);
    }

    public function edit($id = null)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        $requestInfo = $this->request->getJSON();
        if ($this->_tokenValidate()) {
            $job = $this->jobsModel->find($id);
            if (!$job) {
                return $this->failNotFound('Item not found.');
            }
            if ($this->session->USER_ID == $job->USER_ID) {
                $jobEdit = [
                    'JOB_TITLE'             =>  $requestInfo->job_title,
                    'JOB'                   =>  $requestInfo->job,
                    'DATETIME_UPDATED'      =>  date("Y-m-d H:i:s"),
                    'PRIVACY'               =>  $requestInfo->job_privacy,
                ];
                $this->jobsModel->where('JOB_ID', $id)->set($jobEdit)->update();
                return $this->respondDeleted(['message' => 'Tarefa deletada com sucesso!']);
            } else {
                return $this->respondDeleted(['error' => 'Esta tarefa não pertence a você!']);
            }
        } else {
            return $this->respondDeleted(['error' => 'Token inválido!']);
        }
    }

    public function update($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        if ($this->_tokenValidate()) {
            $job = $this->jobsModel->find($id);
            if (!$job) {
                return $this->failNotFound('Item not found.');
            }
            if ($this->session->USER_ID == $job->USER_ID) {
                $this->jobsModel->delete($id);
                return $this->respondDeleted(['message' => 'Tarefa deletada com sucesso!']);
            } else {
                return $this->respondDeleted(['error' => 'Esta tarefa não pertence a você!']);
            }
        } else {
            return $this->respondDeleted(['error' => 'Token inválido!']);
        }
    }

    public function deleteReply($id = null)
    {
        if ($this->_tokenValidate()) {
            $reply = $this->repliesModel->find($id);
            if (!$reply) {
                return $this->failNotFound('Item not found.');
            }
            if ($this->session->USER_ID == $reply->USER_ID) {
                $this->repliesModel->delete($id);
                return $this->respondDeleted(['message' => 'Resposta deletada com sucesso!']);
            } else {
                return $this->respondDeleted(['error' => 'Esta resposta não pertence a você!']);
            }
        } else {
            return $this->respondDeleted(['error' => 'Token inválido!']);
        }
    }

    public function showLikes()
    {
        $response = [];
        if ($this->_tokenValidate()) {
            $json = $this->request->getJSON();

            $likes = $this->likesModel->select('login.USER, login.NAME, login.PROFILE_PIC, likes.LIKE_ID, likes.DATETIME_LIKED')->join('login', 'login.USER_ID = likes.USER_ID')->where('CONTENT_ID', $json->content_id)->where('TYPE', $json->type)->get()->getResultObject();
            foreach ($likes as $like) {
                $response[] = [
                    'like_id'               => $like->LIKE_ID,
                    'profile_pic'           => $like->PROFILE_PIC,
                    'name'                  => $like->NAME,
                    'user'                  => $like->USER,
                    'datetime_liked'        => $this->TimeElapsedString->time_elapsed_string($like->DATETIME_LIKED),
                    'full_datetime_liked'   => date("d/m/Y H:i:s", strtotime($like->DATETIME_LIKED)),
                ];
            }
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }
        return $this->respond($response);
    }
}
