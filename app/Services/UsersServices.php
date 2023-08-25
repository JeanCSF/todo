<?php

namespace App\Services;

use App\Libraries\TimeElapsedStringService;
use App\Libraries\HTMLPurifierService;
use Exception;
use CodeIgniter\Debug\Exceptions;

class UsersServices
{
    private $TimeElapsedString;
    private $HTMLPurifier;

    private $usersModel;
    private $visitsModel;
    private $repliesModel;
    private $likesModel;
    private $jobsModel;

    public function __construct()
    {
        $this->TimeElapsedString = new \App\Libraries\TimeElapsedStringService();
        $this->HTMLPurifier = new HTMLPurifierService();
        $this->session = \Config\Services::session();

        $this->usersModel = new \App\Models\Login();
        $this->visitsModel = new \App\Models\Visits();
        $this->repliesModel = new \App\Models\Replies();
        $this->likesModel = new \App\Models\Likes();
        $this->jobsModel = new \App\Models\Todo();
    }

    public function getFormattedVisits($profileId)
    {
        $response = [];
        $visits = $this->visitsModel->where('PROFILE_USER_ID', $profileId)->findAll();

        foreach ($visits as $visit) {
            $response[] = [
                'view_id'                   => $visit->VIEW_ID,
                'user'                      => $this->usersModel->where('USER_ID', $visit->VISITOR_ID)->get()->getRow('USER'),
                'profile_pic'               => !empty($this->usersModel->where('USER_ID', $visit->VISITOR_ID)->get()->getRow('PROFILE_PIC')) ? $this->usersModel->where('USER_ID', $visit->VISITOR_ID)->get()->getRow('PROFILE_PIC') : '',
                'name'                      => $this->usersModel->where('USER_ID', $visit->VISITOR_ID)->get()->getRow('NAME'),
                'datetime_visited'          => $this->TimeElapsedString->time_elapsed_string($visit->DATETIME_VISITED),
                'full_datetime_visited'     => date("d/m/Y H:i:s", strtotime($visit->DATETIME_VISITED)),
            ];
        }

        return $response;
    }

    public function getFormatedReplies($userId, $currentPage)
    {
        $response = [];
        $user_replies = [];
        $userInfo = $this->usersModel->where('USER_ID', $userId)->get()->getRow();
        $replies = $this->repliesModel->getRepliesDataAndPages($userId, $currentPage);
        $pages = $this->repliesModel->getRepliesDataAndPages($userId);

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
        return $response;
    }

    public function getFormatedLikes($userId, $currentPage)
    {
        $response = [];
        $userInfo = $this->usersModel->where('USER_ID', $userId)->get()->getRow();
        $likes = $this->likesModel->getLikesDataAndPages($userId, $currentPage);
        $pages = $this->likesModel->getLikesDataAndPages($userId);
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
        return $response;
    }
}
