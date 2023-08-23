<?php

namespace App\Controllers;

use App\Libraries\HTMLPurifierService;
use App\Libraries\TimeElapsedStringService;

use CodeIgniter\Debug\Exceptions;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Api_users extends ResourceController
{
    private $HTMLPurifier;
    private $TimeElapsedString;

    private $mainController;
    private $jobsModel;
    private $likesModel;
    private $repliesModel;
    private $usersModel;
    private $visitsModel;

    private $token = 'ihgfedcba987654321';

    public function __construct()
    {
        $this->mainController = new \App\Controllers\Main();

        $this->jobsModel = new \App\Models\Todo();
        $this->likesModel = new \App\Models\Likes();
        $this->repliesModel = new \App\Models\Replies();
        $this->usersModel = new \App\Models\Users();
        $this->visitsModel = new \App\Models\Visits();

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
            $userInfo = $this->usersModel->where('USER', $user)->get()->getRow();
            $userJobs = $this->jobsModel->getJobsDataAndPages($userInfo->USER_ID, $currentPage);
            $pages = $this->jobsModel->getJobsDataAndPages($userInfo->USER_ID);

            try {
                if ($currentPage <= $pages || $pages == 0) {
                    $user_info = [
                        'profile_pic'           => $userInfo->PROFILE_PIC,
                        'user'                  => $userInfo->USER,
                        'name'                  => $userInfo->NAME,
                        'user_id'               => $userInfo->USER_ID,
                    ];

                    foreach ($userJobs as $job) {
                        $user_jobs[] = [
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

    public function saveVisit()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = [];
        if ($this->_tokenValidate()) {
            $checkVisit = $this->visitsModel->getInfoIfAlreadyVisitedProfile($this->request->getPost('user_id'), $this->request->getPost('visitor_id'));

            try {
                if ($this->request->getPost('user_id') == $this->request->getPost('visitor_id')) {
                    $response = [];
                } else if (!empty($checkVisit)) {
                    $newVisit = [
                        'DATETIME_VISITED'          =>  date("Y-m-d H:i:s"),
                    ];

                    $this->visitsModel->table('profile_views')->update($checkVisit, $newVisit);
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Visit Updated'
                    ];
                } else {
                    $newVisit = [
                        'PROFILE_USER_ID'           =>  $this->request->getPost('user_id'),
                        'VISITOR_ID'                =>  $this->request->getPost('visitor_id'),
                        'DATETIME_VISITED'          =>  date("Y-m-d H:i:s"),
                    ];

                    $this->visitsModel->save($newVisit);
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Visit Saved'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'response'  =>  'error',
                    'msg'       =>  'Erro ao Visitar Perfil',
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

    public function getReplies($user_id = null)
    {
        if ($user_id != null) {
            $currentPage = $this->request->getVar('page');
            $response = [];
            $user_replies = [];
            $userInfo = $this->usersModel->where('USER_ID', $user_id)->get()->getRow();
            $replies = $this->repliesModel->getRepliesDataAndPages($user_id, $currentPage);
            $pages = $this->repliesModel->getRepliesDataAndPages($user_id);

            try {
                if ($currentPage <= $pages) {
                    foreach ($replies as $reply) {
                        $user_replies[] = [
                            'reply_id'              => $reply->REPLY_ID,
                            'parent_reply_id'       => $reply->PARENT_REPLY_ID,
                            'reply_id_job'          => $reply->ID_JOB,
                            'reply'                 => $this->HTMLPurifier->html_purify($reply->REPLY),
                            'datetime_replied'      => isset($reply->DATETIME_REPLIED) ? $this->TimeElapsedString->time_elapsed_string($reply->DATETIME_REPLIED) : "",
                            'reply_likes'           => $this->likesModel->getContentLikes($reply->REPLY_ID, 'REPLY'),
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
            $currentPage = $this->request->getVar('page') ?? 1;
            $response = [];
            $userInfo = $this->usersModel->where('USER_ID', $user_id)->get()->getRow();
            $likes = $this->likesModel->getLikesDataAndPages($user_id, $currentPage);
            $pages = $this->likesModel->getLikesDataAndPages($user_id);
            try {
                if ($currentPage <= $pages) {
                    foreach ($likes as $like) {
                        $response[] = [
                            'type'                          =>  $like->TYPE,
                            'content_id'                    =>  $like->CONTENT_ID,
                            'date_liked'                    =>  $this->TimeElapsedString->time_elapsed_string($like->DATETIME_LIKED),
                            'content_liked_user_id'         =>  $like->TYPE == 'POST' ? $this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('USER_ID') : $this->repliesModel->where('REPLY_ID', $like->CONTENT_ID)->get()->getRow('USER_ID'),
                            'content_liked_user'            =>  $like->TYPE == 'POST' ? $this->usersModel->where('USER_ID', $this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('USER_ID'))->get()->getRow('USER') : $this->usersModel->where('USER_ID', $this->repliesModel->where('REPLY_ID', $like->CONTENT_ID)->get()->getRow('USER_ID'))->get()->getRow('USER'),
                            'content_liked_user_name'       =>  $like->TYPE == 'POST' ? $this->usersModel->where('USER_ID', $this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('USER_ID'))->get()->getRow('NAME') : $this->usersModel->where('USER_ID', $this->repliesModel->where('REPLY_ID', $like->CONTENT_ID)->get()->getRow('USER_ID'))->get()->getRow('NAME'),
                            'content_liked_user_img'        =>  $like->TYPE == 'POST' ? $this->usersModel->where('USER_ID', $this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('USER_ID'))->get()->getRow('PROFILE_PIC') : $this->usersModel->where('USER_ID', $this->repliesModel->where('REPLY_ID', $like->CONTENT_ID)->get()->getRow('USER_ID'))->get()->getRow('PROFILE_PIC'),
                            'content_liked_title'           =>  $like->TYPE == 'POST' ? $this->HTMLPurifier->html_purify($this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('JOB_TITLE')) : '',
                            'content_liked_text'            =>  $like->TYPE == 'POST' ? $this->HTMLPurifier->html_purify($this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('JOB')) : $this->repliesModel->where('REPLY_ID', $like->CONTENT_ID)->get()->getRow('REPLY'),
                            'content_liked_created'         =>  $like->TYPE == 'POST' ? $this->TimeElapsedString->time_elapsed_string($this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('DATETIME_CREATED')) : $this->TimeElapsedString->time_elapsed_string($this->repliesModel->where('REPLY_ID', $like->CONTENT_ID)->get()->getRow('DATETIME_REPLIED')),
                            'content_liked_finished'        =>  $like->TYPE == 'POST' ? !empty($this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('DATETIME_FINISHED')) ? 'Finalizado: ' . $this->TimeElapsedString->time_elapsed_string($this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('DATETIME_FINISHED')) : '' : '',
                            'content_liked_privacy'         =>  $like->TYPE == 'POST' ? $this->jobsModel->where('ID_JOB', $like->CONTENT_ID)->get()->getRow('PRIVACY') : '',
                            'content_liked_num_likes'       =>  $like->TYPE == 'POST' ? $this->likesModel->getContentLikes($like->CONTENT_ID, 'POST') : $this->likesModel->getContentLikes($like->CONTENT_ID, 'REPLY'),
                            'content_liked_num_comments'    =>  $like->TYPE == 'POST' ? $this->repliesModel->countJobReplies($like->CONTENT_ID) : $this->repliesModel->countRepliesOfThisReply($like->CONTENT_ID),
                            'user_liked'                    =>  $like->TYPE == 'POST' ? $this->likesModel->checkUserLikedJob($like->CONTENT_ID, $this->session->USER_ID) : $this->likesModel->checkUserLikedReply($like->CONTENT_ID, $this->session->USER_ID)
                        ];
                    }
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
