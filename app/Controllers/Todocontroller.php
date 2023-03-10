<?php

namespace App\Controllers;

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
                $mensagem = [
                    'mensagem' => 'Tarefa Adicionada com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            } else {
                $mensagem = [
                    'mensagem' => 'Não foi possível adicionar tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            }
        }
    }

    public function editJobSubmit()
    {
        $job = new Todo();
        $params = [
            'ID_JOB' => $this->request->getPost('id_job'),
            'JOB' => $this->request->getPost('job_name'),
        ];
        if (!empty($params)) {
            if ($job->editJob($params)) {
                $mensagem = [
                    'mensagem' => 'Tarefa alterada com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            } else {
                $mensagem = [
                    'mensagem' => 'Não foi possível editar tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
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
                $mensagem = [
                    'mensagem' => 'Tarefa concluída com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            } else {
                $mensagem = [
                    'mensagem' => 'Não foi possível concluir tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            }
        }
    }

    public function delete($id_job = -1)
    {
        $job = new Todo();
        $params = [
            'ID_JOB' => $id_job,
        ];
        if (!empty($params)) {
            if ($job->deleteJob($params)) {
                $mensagem = [
                    'mensagem' => 'Tarefa excluída com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            } else {
                $mensagem = [
                    'mensagem' => 'erro ao excluír tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->to(base_url('/'));
            }
        }
    }
}
