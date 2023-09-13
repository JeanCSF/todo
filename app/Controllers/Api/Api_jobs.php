<?php

namespace App\Controllers\Api;

use App\Libraries\HTMLPurifierService;
use App\Libraries\TimeElapsedStringService;
use App\Services\JobsServices;
use CodeIgniter\Debug\Exceptions;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Api_jobs extends ResourceController
{
    private $HTMLPurifier;
    private $TimeElapsedString;
    private $jobsServices;

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
        $this->jobsServices = new JobsServices();
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
            $response = $this->jobsServices->getFormatedHomePosts($currentPage);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Invalid Token']);
        }
    }

    public function likeContent()
    {
        if ($this->_tokenValidate()) {
            $requestInfo = $this->request->getJSON();
            $response = $this->jobsServices->like($requestInfo);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Invalid Token']);
        }
    }

    public function commentContent()
    {
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
            $requestInfo = $this->request->getJSON();
            $response = $this->jobsServices->comment($requestInfo);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Invalid Token']);
        }
    }

    public function show($id = null)
    {
        if ($id != null && $this->_tokenValidate()) {
            $response = $this->jobsServices->getFormatedPost($id);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Invalid Token']);
        }
    }

    public function showReply($id = null)
    {
        if ($id != null && $this->_tokenValidate()) {
            $response = $this->jobsServices->getFormatedReply($id);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Invalid Token']);
        }
    }


    public function new()
    {
        //
    }

    public function create()
    {
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
            $requestInfo = $this->request->getJSON();
            $response = $this->jobsServices->createJob($requestInfo);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Invalid Token']);
        }
    }

    public function edit($id = null)
    {
    }

    public function update($id = null)
    {
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
            $requestInfo = $this->request->getJSON();
            $response = $this->jobsServices->updateJob($id, $requestInfo);
            return $this->respond($response);
        } else {
            return $this->respond(['error' => 'Token inválido!']);
        }
    }

    public function delete($id = null)
    {
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
            $type = $this->request->getVar('type');
            $response = $this->jobsServices->deleteContent($id, $type);
            return $this->respondDeleted($response);
        } else {
            return $this->respondDeleted(['error' => 'Token inválido!']);
        }
    }

    public function showLikes()
    {
        if (isset($this->session->USER_ID) && $this->_tokenValidate()) {
            $requestInfo = $this->request->getJSON();
            $response = $this->jobsServices->getFormatedLikes($requestInfo);
            return $this->respond($response);
        } else {
            return $this->respondDeleted(['error' => 'Token inválido!']);
        }
    }
}
