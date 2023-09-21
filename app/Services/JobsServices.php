<?php

namespace App\Services;

use App\Libraries\TimeElapsedStringService;
use App\Libraries\HTMLPurifierService;
use Exception;
use CodeIgniter\Debug\Exceptions;

class JobsServices
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

    public function createJob($requestInfo)
    {
        try {
            $newJob = [
                'USER_ID'               =>  $requestInfo->user_id,
                'JOB_TITLE'             =>  $requestInfo->job_title,
                'JOB'                   =>  $requestInfo->job,
                'DATETIME_CREATED'      =>  date("Y-m-d H:i:s"),
                'PRIVACY'               =>  $requestInfo->job_privacy,
            ];
            if ($this->jobsModel->save($newJob)) {
                return ['message' => 'Tarefa criada com sucesso!'];
            } else {
                return ['error' => 'Erro ao criar tarefa!'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getFormatedHomePosts($currentPage)
    {
        $jobs = $this->jobsModel->getIndexDataAndPages($currentPage);
        $pages = $this->jobsModel->getIndexDataAndPages();
        foreach ($jobs as $key => $job) {
            if ($currentPage <= $pages || $pages == 0) {
                $response[] = [
                    'profile_pic'           => $job->PROFILE_PIC,
                    'user'                  => $job->USER,
                    'name'                  => $job->NAME,
                    'user_id'               => $job->USER_ID,
                    'job_id'                => $job->ID_JOB,
                    'job_title'             => $this->HTMLPurifier->html_purify($job->JOB_TITLE),
                    'job'                   => nl2br($this->HTMLPurifier->html_purify($job->JOB)),
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

        return $response;
    }

    public function getFormatedPost($id)
    {
        try {
            $response = [];
            $jobs = $this->jobsModel->getJob($id);
            $comments = $this->repliesModel->getJobReplies($id);
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
                        'job'                   => nl2br($this->HTMLPurifier->html_purify($job['JOB'])),
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
                        'reply'                 => nl2br($this->HTMLPurifier->html_purify($comment['comment'])),
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
                    'msg'       =>  'Post Not Found'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Error on Find Post',
                'errors'    =>  [
                    'exception' =>  $e->getMessage()
                ],
            ];
        }
        return $response;
    }

    public function getFormatedReply($id)
    {
        try {
            $response = [];
            $replyInfo = $this->repliesModel->getReply($id);
            $comments = $this->repliesModel->getReplyReplies($id);
            if (!empty($replyInfo)) {
                $comment_info = [];
                foreach ($replyInfo as $reply) {
                    $reply_info = [
                        'profile_pic'           => $reply['PROFILE_PIC'],
                        'user'                  => $reply['USER'],
                        'name'                  => $reply['NAME'],
                        'user_id'               => $reply['USER_ID'],
                        'reply_id'              => $reply['REPLY_ID'],
                        'reply'                 => nl2br($this->HTMLPurifier->html_purify($reply['REPLY'])),
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
                    'msg'       =>  'Reply Not Found'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'response'  =>  'error',
                'msg'       =>  'Error on Find Reply',
                'errors'    =>  [
                    'exception' =>  $e->getMessage()
                ],
            ];
        }
        return $response;
    }

    public function updateJob($id, $requestInfo)
    {
        try {
            $job = $this->jobsModel->find($id);
            if (!$job) {
                return ['error' => 'Item not found'];
            }
            if ($this->session->USER_ID == $job->USER_ID) {
                $jobEdit = [
                    'JOB_TITLE'             =>  $requestInfo->job_title,
                    'JOB'                   =>  $requestInfo->job,
                    'DATETIME_UPDATED'      =>  date("Y-m-d H:i:s"),
                    'PRIVACY'               =>  $requestInfo->job_privacy,
                ];
                $this->jobsModel->table('jobs')->update($id, $jobEdit);
                return ['message' => 'Tarefa atualizada com sucesso!'];
            } else {
                return ['error' => 'Esta tarefa não pertence a você!'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function finishJob($id)
    {
        try {
            $job = $this->jobsModel->find($id);
            if (!$job) {
                return ['error' => 'Item not found'];
            }
            if ($this->session->USER_ID == $job->USER_ID) {
                $jobFinish = [
                    'DATETIME_FINISHED'     =>  date("Y-m-d H:i:s"),
                    'DATETIME_UPDATED'      =>  date("Y-m-d H:i:s"),
                ];
                $this->jobsModel->table('jobs')->update($id, $jobFinish);
                return ['message' => 'Tarefa finalizada com sucesso!'];
            } else {
                return ['error' => 'Esta tarefa não pertence a você!'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function replyUpdate($id, $requestInfo)
    {
        try {
            $reply = $this->repliesModel->find($id);
            if (!$reply) {
                return ['error' => 'Item not found'];
            }
            if ($this->session->USER_ID == $reply->USER_ID) {
                $replyEdit = [
                    'REPLY'                   =>  $requestInfo->reply,
                    'DATETIME_REPLIED'      =>  date("Y-m-d H:i:s"),
                ];
                $this->repliesModel->table('replies')->update($id, $replyEdit);
                return ['message' => 'Resposta atualizada com sucesso!'];
            } else {
                return ['error' => 'Esta resposta não pertence a você!'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteContent($id, $type)
    {
        try {
            $content = $type == 'POST' ? $this->jobsModel->find($id) : $this->repliesModel->find($id);
            if (!$content) {
                return ['error' => 'Item not found'];
            }
            if ($this->session->USER_ID == $content->USER_ID) {
                if ($type == 'POST') {
                    $this->jobsModel->delete($id);
                    return ['message' => 'Tarefa deletada com sucesso!'];
                } else {
                    $this->repliesModel->delete($id);
                    return ['message' => 'Resposta deletada com sucesso!'];
                }
            } else {
                return ['error' => 'Esta tarefa não pertence a você!'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function like($requestInfo)
    {
        try {
            $newLike = [
                'LIKE_ID'           =>  $this->session->USER . '_' . date("Y_m_d_H_i_s"),
                'USER_ID'           =>  $requestInfo->user_id,
                'CONTENT_ID'        =>  $requestInfo->content_id,
                'TYPE'              =>  $requestInfo->type_content,
                'DATETIME_LIKED'    =>  date("Y-m-d H:i:s"),
            ];

            $checkLike = $this->likesModel->getInfoIfAlreadyLikedContent($newLike['CONTENT_ID'], $newLike['USER_ID'], $newLike['TYPE']);
            if (!empty($checkLike)) {
                $this->likesModel->where('LIKE_ID', $checkLike[0]->LIKE_ID)->delete();
                return ['message' => 'Unliked Post'];
            } else {
                $this->likesModel->save($newLike);
                return ['message' => 'Liked Post'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getFormatedLikes($requestInfo)
    {
        try {
            $likes = $this->likesModel->getContentLikesInfo($requestInfo->content_id, $requestInfo->type);
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
            return $response;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function comment($requestInfo)
    {
        try {
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

            if (!empty($newComment)) {
                $this->repliesModel->save($newComment);
                return ['message' => 'Commented Post'];
            } else {
                return ['message' => 'Error on Comment'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
