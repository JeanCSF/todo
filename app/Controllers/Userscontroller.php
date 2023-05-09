<?php

namespace App\Controllers;

use App\Models\Login;
use App\Models\Todo;
use App\Models\Users;

class Userscontroller extends BaseController
{
    public function upload()
    {
        $img = $this->request->getFile('userfile');

        if (!$this->validate([
            'userfile'          => 'uploaded[userfile]|is_image[userfile]|ext_in[userfile,jpeg,jpg,png]|max_dims[userfile,1920,1080]|max_size[userfile,2048]'
        ], [
            'userfile'  => [
                'uploaded'      => 'Escolha uma Imagem',
                'is_image'      => 'Arquivo não é de imagem',
                'ext_in'        => 'Extensão ' . $img->getExtension() . ' não é suportada',
                'max_dims'      => 'Resolução máxima é 1920x1080'
            ]
        ])) {
            session()->setFlashdata('errors', $this->validator->getErrors());
        }


        if (!$img->hasMoved()) {
            $users = new Users();
            $img_name = $img->getRandomName();
            $id = $_SESSION['USER_ID'];
            if ($users->saveProfilePic($id, $img_name)) {

                $img->store('../../public/assets/img/profiles_pics', $img_name);

                session()->setFlashdata('uploaded', 'Uploaded Sucessfully');

                return redirect()->back();
            }
        }
    }

    public function profile($id)
    {
        if ($this->session->has('USER_ID')) {
            $id = base64_decode($id);
            $users = new Users();
            $jobs = new Todo();
            $data = [
                'userData'              => $users->getUser($id),
                'userTasks'             => $jobs->getUserJobs($id),
                'alltasks'              => $jobs->countAllUserJobs($id),
                'alldone'               => $jobs->countAllUserDoneJobs($id),
                'notdone'               => $jobs->countAllUserNotDoneJobs($id),
                'pager'                 => $jobs->pager,

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
            if (!$login->signUpCheckUser($post)) {
                $data['userData']  = $post;
                $msg['msg'] = 'Usuário ou email já cadastrado!';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
                return view('users/form_users', $data);
            } else if ($users->addUser($post)) {
                $msg['msg'] = 'Usuário cadastrado com sucesso!';
                $msg['type'] = 'alert-success';
                $this->session->setFlashdata('msg', $msg);
                return redirect()->to(base_url('userscontroller/users'));
            }
        }
        echo view('users/form_users');
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $users = new Users();
        $post = $this->request->getPost();

        if (!empty($post)) {
            if ($users->editUser($id, $post)) {
                $msg['msg'] = 'Usuário Atualizado com sucesso!';
                $msg['type'] = 'alert-success';
                $this->session->setFlashdata('msg', $msg);
                return redirect()->to(base_url('userscontroller/users'));
            }
        }

        $data = [
            'user'          => $users->getUser($id),
            'edit'          => true,
        ];
        echo view('users/form_users', $data);
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
