<?php

namespace App\Controllers\Api;

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
        if ($user != null && $this->_tokenValidate()) {

            $currentPage = $this->request->getVar('page') ?? 1;
            $response = $this->usersServices->getFormatedUser($user, $currentPage);
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Token inválido',
            ];
        }

        return $this->respond($response);
    }

    public function saveVisit()
    {
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {

            $requestInfo = $this->request->getJSON();
            $response = $this->usersServices->saveProfileVIsit($requestInfo);
        } else {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Invalid Token/Not Logged In',
            ];
        }
        return $this->respond($response);
    }

    public function showVisits()
    {
        $response = [];
        if ($this->_tokenValidate()) {

            $response = $this->usersServices->getFormattedVisits($this->request->getJSON('profile_id'));
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
        if ($user_id != null && $this->_tokenValidate()) {
            $currentPage = $this->request->getVar('page') ?? 1;

            $response = ['likes' => $this->usersServices->getFormatedLikes($user_id, $currentPage)];

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
