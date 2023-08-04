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
    private $commentsModel;
    private $token = 'ihgfedcba987654321';

    public function __construct()
    {
        $this->mainController = new \App\Controllers\Main();
        $this->jobsModel = new \App\Models\Todo();
        $this->likesModel = new \App\Models\Likes();
        $this->commentsModel = new \App\Models\Comments();
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
                    'job_num_comments'      => $this->commentsModel->countJobComments($job->ID_JOB),
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
                'ID_JOB'            =>  $this->request->getPost('job_id'),
                'DATETIME_LIKED'    =>  date("Y-m-d H:i:s"),
            ];
            $checkLike = $this->likesModel->checkUserLiked($newLike['ID_JOB'], $newLike['USER_ID']);

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
            $newComment = [
                'COMMENT_ID'            =>  $this->session->USER . date("Y-m-d H:i:s"),
                'USER_ID'               =>  $this->request->getPost('user_id'),
                'ID_JOB'                =>  $this->request->getPost('job_id'),
                'COMMENT'               =>  $this->request->getPost('job_comment'),
                'DATETIME_COMMENTED'    =>  date("Y-m-d H:i:s"),
            ];
            try {
                if (!empty($newComment)) {
                    $response = [
                        'response'  =>  'success',
                        'msg'       =>  'Commented Post'
                    ];
                    $this->commentsModel->save($newComment);
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
                    ->join('login', 'login.USER_ID = jobs.USER_ID')
                    ->where('jobs.PRIVACY', true)->where('jobs.ID_JOB', $id)->get()->getResultArray();

                try {
                    if (!empty($jobs)) {
                        foreach ($jobs as $key => $job) {
                            $response = [
                                'response'    =>  [
                                    'response'              =>  'success',
                                    'msg'                   =>  'Dados deste post',
                                ],
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
                                'job_num_comments'      => $this->commentsModel->countJobComments($job['ID_JOB']),
                                'user_liked'            => $this->likesModel->checkUserLikedJob($job['ID_JOB'], $this->session->USER_ID),
                                'job_comments'          => $this->commentsModel->where('ID_JOB',$job['ID_JOB'])->findAll(),
                            ];
                        }
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
