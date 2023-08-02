<?php

namespace App\Controllers;

use App\Models\Likes;
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

    public function likeJob($id_job)
    {
        $like = new Likes();

        if (!empty($id_job)) {
            if ($like->newLikeJob($id_job)) {
                $msg = [
                    'msg' => 'Like',
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

    public function countJobLikes()
    {
        $post = $this->request->getPost();
        if (!empty($post)) {
            $likes = new Likes();
            $term = filter_input(INPUT_POST, 'term', FILTER_SANITIZE_STRING);
            $total_likes = $likes->select('LIKE_ID')->where('ID_JOB', $term)->countAllResults();
            $result = [
                'likes'     => $total_likes,
            ];
            return $this->response->setJSON($result);
        }
    }
}
