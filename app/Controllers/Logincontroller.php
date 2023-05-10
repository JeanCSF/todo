<?php

namespace App\Controllers;

use App\Models\login;

class Logincontroller extends BaseController
{

    public function signUp()
    {
        $email = \Config\Services::email();
        $login = new login();
        $post = $this->request->getPost();
        if (!empty($post)) {
            if ($this->checkPass($post)) {
                $data['userData']  = $post;
                $msg['msg'] = 'Senhas digitadas não conferem!';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
                return view('login/signup', $data);
            }
            if (!$login->signUpCheckUser($post)) {
                $data['userData']  = $post;
                $msg['msg'] = 'Usuário ou email já cadastrado!';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
                return view('login/signup', $data);
            } else if ($login->signUpCreateAccount($post)) {
                $key = base64_encode($post['email'] . date('Y-m-d H:i:s'));
                $email = \Config\Services::email();

                $config = [
                    'protocol'      => 'smtp',
                    'SMTPHost'      => 'sandbox.smtp.mailtrap.io',
                    'SMTPUser'      => '6a29d381cdc759',
                    'SMTPPass'      => '60e1758b41e608',
                    'SMTPPort'      => '2525',
                    'mailType'      => 'html'
                ];

                $email->initialize($config);

                $email->setSubject($post['name'] . ', confirme seu e-mail para continuar');
                $email->setTo($post['email']);
                $emailData = [
                    'key'   => $key,
                    'post'  => $post
                ];
                $email->setMessage(view('login/email_template', $emailData));

                if ($email->send()) {
                    $msg['msg'] = 'Cadastro criado, acesse seu email para confirmar a conta!';
                    $msg['type'] = 'alert-success';
                    $this->session->setFlashdata('msg', $msg);
                    return redirect()->to(base_url('/'));
                }
            }
        }
        echo view('login/signup');
    }

    public function emailConfirm()
    {
        $key = htmlspecialchars($_GET['key']);
        $login = new login();
        if (!empty($key)) {
            $row = $login->getUserIdByKey($key);
            if (isset($row)) {
                $params = [
                    'USER_ID'               => $row->USER_ID,
                    'ACTIVATION_KEY'        => null,
                ];
                if ($login->activateUser($params)) {
                    $msg = [
                        'msg' => 'E-mail confirmado com sucesso!',
                        'type' => 'alert-success',
                    ];
                    $this->session->setFlashdata('msg', $msg);
                } else {
                    $msg['msg'] = 'Erro ao confirmar e-mail';
                    $msg['type'] = 'alert-danger';
                    $this->session->setFlashdata('msg', $msg);
                }
            }
        }
        echo view('login/confirmation');
    }

    public function checkPass($post)
    {
        $pass1 = $post['pass'];
        $pass2 = $post['pass2'];

        return $pass1 != $pass2 ? true : false;
    }

    public function login()
    {
        $login = new login();

        $post = $this->request->getPost();
        if (!empty($post)) {
            if ($login->checkLogin($post)) {
                $row = $login->getUserData($post['user']);
                if ($row->ACTIVATION == 0) {
                    $msg = [
                        'msg' => 'Por favor ative sua conta para acessar!',
                        'type' => 'alert-danger',
                    ];
                    $this->session->setFlashdata('msg', $msg);
                    return view('login/login');
                } else {
                    $data = [
                        'USER_ID'       => $row->USER_ID,
                        'USER'          => $row->USER,
                        'EMAIL'         => $row->EMAIL,
                        'NAME'          => $row->NAME,
                        'SU'            => $row->SU,
                    ];
                    $this->session->set($data);
                    $msg = [
                        'msg' => 'Bem vindo',
                        'type' => 'alert-success',
                    ];
                    $msg['msg'] = 'Bem vindo ' . $_SESSION['NAME'] . '!';
                    $this->session->setFlashdata('msg', $msg);
                    return redirect()->to(base_url('/'));
                }
            } else {
                $msg['msg'] = 'Usuário ou senha inválido';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
            }
        }
        echo view('login/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}
