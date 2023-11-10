<?php

namespace App\Controllers;

use App\Models\Likes;
use App\Models\Replies;
use App\Models\Todo;
use CodeIgniter\Controller;

class TodoController extends BaseController
{
    public function jobDone($id_job)
    {
        $job = new Todo();
        $params = [
            'ID_JOB' => $id_job,
        ];
        if (!empty($params)) {
            if ($job->finishJob($params)) {
                $msg = [
                    'msg' => 'Tarefa concluída com sucesso',
                    'type' => 'alert-success',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            } else {
                $msg = [
                    'msg' => 'Não foi possível concluir tarefa',
                    'type' => 'alert-danger',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            }
        }
    }

    public function job($job_id)
    {
        $jobs = new Todo();
        $job = $jobs->find($job_id);
        if ($job) {
            if (isset($_SESSION['USER_ID'])) {
                $data = [
                    'pageTitle'     =>  $job->JOB_TITLE,
                    'job_id'        =>  $job_id,
                ];
                return view('post', $data);
            } else {
                $this->main();
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function reply($reply_id)
    {
        $replies = new Replies();
        $reply = $replies->find($reply_id);
        if ($reply) {
            if (isset($_SESSION['USER_ID'])) {
                $data = [
                    'pageTitle'     =>  $reply->REPLY,
                    'reply_id'      =>  $reply_id,
                ];
                return view('reply', $data);
            } else {
                $this->main();
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
