<?php

namespace App\Controllers;

use App\Models\Login;
use App\Models\Todo;
use App\Models\Users;

class Userscontroller extends BaseController
{
    public function profile($id)
    {
        if ($this->session->has('USER_ID')) {
            $id = base64_decode($id);
            $users = new Users();
            $jobs = new Todo();
            $profile = $users->getUser($id);
            $tasks = $jobs->getUserJobs($id);
            $alltasks = $jobs->countAllUserJobs($id);
            $data = [
                'userData'          => $profile,
                'userTasks'         => $tasks,
                'pager'             => $jobs->pager,
                'alltasks'          => $alltasks,

            ];
            echo view('users/profile', $data);
        } else {
            $this->login();
        }
    }

    public function login()
    {
        $session = session();
        if ($session->has('USER_ID')) {
            redirect()->to(base_url('/'));
        } else {
            echo view('main');
        }
    }

    public function users()
    {
        $users = new Users();
        $data = [
            'users'     => $users->getAll(),
        ];
        echo view('users/all_users', $data);
    }

    public function newUser()
    {
        $users = new Users();
        $login = new Login();
        $post = $this->request->getPost();
        if (!empty($post)) {
            if ($login->signUpCheckUser($post)) {
                $data['userData']  = $post;
                $msg['msg'] = 'Usuário ou email já cadastrado!';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
                return view('users/form_users', $data);
            } else if ($users->addUser($post)) {
                $msg['msg'] = 'Usuário cadastrado com sucesso!';
                $msg['type'] = 'alert-success';
                $this->session->setFlashdata('msg', $msg);
                redirect()->to(base_url('users/all_users'));
            }
        }
        echo view('users/form_users');
    }

    public function delete()
    {
        $user = new Users();
        $post = $this->request->getPost();
        if (!empty($post)) {
            if ($user->deleteUser($post)) {
                $msg = [
                    'msg' => 'Usuário excluído com sucesso',
                    'type' => 'alert-success',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            } else {
                $msg = [
                    'msg' => 'Erro ao excluír usuário',
                    'type' => 'alert-danger',
                ];
                $this->session->setFlashdata('msg', $msg);
                return redirect()->back();
            }
        }
    }
}
