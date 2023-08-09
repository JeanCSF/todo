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
            $currentPage = $this->request->getVar('page');
            $response = [];
            $user_jobs = [];
            $userInfo = $this->usersModel->select('*')->where('USER', $user)->get()->getResultObject();
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
                ->where('jobs.PRIVACY', true)->where('jobs.USER_ID', $userInfo[0]->USER_ID)->orderBy('NUM_LIKES DESC, NUM_REPLIES DESC, jobs.DATETIME_CREATED DESC')->paginate(10, '', $currentPage);

            try {
                if (!empty($userInfo)) {
                    $user_info = [
                        'profile_pic'           => $userInfo[0]->PROFILE_PIC,
                        'user'                  => $userInfo[0]->USER,
                        'name'                  => $userInfo[0]->NAME,
                        'user_id'               => $userInfo[0]->USER_ID,
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
                        'user_jobs'     =>  $user_jobs
                    ];
                } else {
                    $response = [
                        'response'  =>  'error',
                        'msg'       =>  'Usuário não encontrado'
                    ];
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
            $user_jobs = [];
            $replies = $this->repliesModel->select('login.PROFILE_PIC
                            ,login.USER
                            ,login.NAME
                            ,login.USER_ID
                            ,replies.REPLY_ID
                            ,replies.REPLY
                            ,replies.DATETIME_REPLIED')
                    ->join('login', 'login.USER_ID = replies.USER_ID')
                    ->where('replies.USER_ID', $user_id)->paginate(10, '', $currentPage);

            try {
                if (!empty($replies)) {
                    // $user_info = [
                    //     'profile_pic'           => $userInfo[0]->PROFILE_PIC,
                    //     'user'                  => $userInfo[0]->USER,
                    //     'name'                  => $userInfo[0]->NAME,
                    //     'user_id'               => $userInfo[0]->USER_ID,
                    // ];

                    // foreach ($userJobs as $job) {
                    //     $user_jobs[] = [
                    //         'job_id'                => $job->ID_JOB,
                    //         'job_title'             => $job->JOB_TITLE,
                    //         'job'                   => $job->JOB,
                    //         'job_created'           => isset($job->DATETIME_CREATED) ? date("d/m/Y", strtotime($job->DATETIME_CREATED)) : "",
                    //         'job_updated'           => isset($job->DATETIME_UPDATED) ? date("d/m/Y", strtotime($job->DATETIME_UPDATED)) : "",
                    //         'job_finished'          => isset($job->DATETIME_FINISHED) ? date("d/m/Y", strtotime($job->DATETIME_FINISHED)) : "",
                    //         'job_privacy'           => $job->PRIVACY,
                    //         'job_likes'             => $job->NUM_LIKES,
                    //         'job_num_comments'      => $job->NUM_REPLIES,
                    //         'user_liked'            => $this->likesModel->checkUserLikedJob($job->ID_JOB, $this->session->USER_ID),
                    //     ];
                    // }

                    $response = [
                        'replies'       =>  $replies
                    ];
                } else {
                    $response = [
                        'response'  =>  'error',
                        'msg'       =>  'Usuário não encontrado'
                    ];
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
