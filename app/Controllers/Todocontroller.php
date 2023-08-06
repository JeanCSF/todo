<?php

namespace App\Controllers;

use App\Models\Likes;
use App\Models\Replies;
use App\Models\Todo;
use CodeIgniter\Controller;

class Todocontroller extends BaseController
{

    public function newJobSubmit()
    {

        $job = new Todo();
        $post = $this->request->getPost();


        if (!empty($post)) {
            if ($job->insertJob($post)) {
                $msg = [
                    'msg' => 'Tarefa Adicionada com sucesso',
                    'type' => 'alert-success',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            } else {
                $msg = [
                    'msg' => 'Não foi possível adicionar tarefa',
                    'type' => 'alert-danger',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            }
        }
    }

    public function editJobSubmit()
    {
        $job = new Todo();
        $post = $this->request->getPost();

        if (!empty($post)) {
            if ($job->editJob($post)) {
                $msg = [
                    'msg' => 'Tarefa alterada com sucesso',
                    'type' => 'alert-success',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            } else {
                $msg = [
                    'msg' => 'Não foi possível editar tarefa',
                    'type' => 'alert-danger',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            }
        }
    }

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

    public function delete()
    {
        $job = new Todo();
        $post = $this->request->getPost();
        if (!empty($post)) {
            if ($job->deleteJob($post)) {
                $msg = [
                    'msg' => 'Tarefa excluída com sucesso',
                    'type' => 'alert-success',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            } else {
                $msg = [
                    'msg' => 'erro ao excluír tarefa',
                    'type' => 'alert-danger',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            }
        }
    }

    public function changePrivacy()
    {
        $job = new Todo();
        $post = $this->request->getPost();

        if (!empty($post)) {
            if ($job->changeJobPrivacy($post)) {
                $msg = [
                    'msg' => 'Privacidade alterada com com sucesso',
                    'type' => 'alert-success',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            } else {
                $msg = [
                    'msg' => 'Não foi possível adicionar tarefa',
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
        if (isset($_SESSION['USER_ID'])) {
            $data = [
                'pageTitle'     =>  $job->JOB_TITLE,
                'job_id'        =>  $job_id,
            ];
            return view('post', $data);
        } else {
            $this->main();
        }
    }

    public function reply($reply_id)
    {
        $replies = new Replies();
        $replie = $replies->find($reply_id);
        if (isset($_SESSION['USER_ID'])) {
            $data = [
                'pageTitle'     =>  $replie->REPLY,
                'reply_id'      =>  $reply_id,
            ];
            return view('reply', $data);
        } else {
            $this->main();
        }
    }
}
