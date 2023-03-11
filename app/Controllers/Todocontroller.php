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
                return redirect()->back();
            } else {
                $mensagem = [
                    'mensagem' => 'Não foi possível adicionar tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
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
                $mensagem = [
                    'mensagem' => 'Tarefa alterada com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->back();
            } else {
                $mensagem = [
                    'mensagem' => 'Não foi possível editar tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
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
                $mensagem = [
                    'mensagem' => 'Tarefa concluída com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->back();

            } else {
                $mensagem = [
                    'mensagem' => 'Não foi possível concluir tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
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
                $mensagem = [
                    'mensagem' => 'Tarefa excluída com sucesso',
                    'tipo' => 'alert-success',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->back();
            } else {
                $mensagem = [
                    'mensagem' => 'erro ao excluír tarefa',
                    'tipo' => 'alert-danger',
                ];
                $this->session->setFlashdata('mensagem', $mensagem);
                return redirect()->back();
            }
        }
    }
}
