<?php

namespace App\Controllers;

use App\Libraries\HTMLPurifierService;
use App\Libraries\TimeElapsedStringService;
use App\Services\UsersServices;
use CodeIgniter\Debug\Exceptions;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Api_users extends ResourceController
{
    private $HTMLPurifier;
    private $TimeElapsedString;
    private $usersServices;

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
        $this->usersServices = new UsersServices();
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
                        'user_jobs'     =>  isset($user_jobs)? $user_jobs : null,
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
                'msg'       =>  'Invalid Token',
            ];
        }
        return $this->respond($response);
    }

    public function showVisits()
    {
        $response = [];
        if ($this->_tokenValidate()) {

            $response = $this->usersServices->getFormattedVisits($this->request->getVar('profile_id'));
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Invalid Token',
            ];
        }
        return $this->respond($response);
    }

    public function getReplies($user_id = null)
    {
        $response = [];
        if ($user_id != null && $this->_tokenValidate()) {
            $currentPage = $this->request->getVar('page') ?? 1;

            $response = $this->usersServices->getFormatedReplies($user_id, $currentPage);

            return $this->respond($response);
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Invalid User/Token',
            ];
        }
    }

    public function getLikes($user_id = null)
    {
        if ($user_id != null) {
            $currentPage = $this->request->getVar('page') ?? 1;
            
            $response = $this->usersServices->getFormatedLikes($user_id, $currentPage);

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
