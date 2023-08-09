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
            $response = [];
                $userInfo = $this->usersModel->select('*')->where('USER', $user)->get()->getResultObject();

            try {
                if (!empty($userInfo)) {
                    foreach ($userInfo as $user) {
                        $response[] = [
                            'profile_pic'           => $user->PROFILE_PIC,
                            'user'                  => $user->USER,
                            'name'                  => $user->NAME,
                            'user_id'               => $user->USER_ID,
                        ];
                    }
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
