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
                $mensagem['mensagem'] = 'Senhas digitadas não conferem!';
                $mensagem['tipo'] = 'alert-danger';
                $this->session->setFlashdata('mensagem', $mensagem);
                return view('login/signup', $data);
            }
            if (!$login->signUpCheckUser($post)) {
                $data['userData']  = $post;
                $mensagem['mensagem'] = 'Usuário ou email já cadastrado!';
                $mensagem['tipo'] = 'alert-danger';
                $this->session->setFlashdata('mensagem', $mensagem);
                return view('login/signup', $data);
            } else if ($login->signUpCreateAccount($post)) {
                $key = base64_encode($post['txtEmail'] . date('Y-m-d H:i:s'));
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

                $email->setSubject($post['txtName'] . ', confirme seu e-mail para continuar');
                $emailData = [
                    'key'   => $key,
                    'post'  => $post
                ];
                $email->setMessage(view('login/email_template', $emailData));

                if ($email->send()) {
                    $mensagem['mensagem'] = 'Cadastro criado, acesse seu email para confirmar a conta!';
                    $mensagem['tipo'] = 'alert-success';
                    $this->session->setFlashdata('mensagem', $mensagem);
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
                    $mensagem = [
                        'mensagem' => 'E-mail confirmado com sucesso!',
                        'tipo' => 'alert-success',
                    ];
                    $this->session->setFlashdata('mensagem', $mensagem);
                } else {
                    $mensagem['mensagem'] = 'Erro ao confirmar e-mail';
                    $mensagem['tipo'] = 'alert-danger';
                    $this->session->setFlashdata('mensagem', $mensagem);
                }
            }
        }
        echo view('login/confirmation');
    }

    public function checkPass($post)
    {
        $pass1 = $post['txtPass'];
        $pass2 = $post['txtPass2'];

        return $pass1 != $pass2 ? true : false;
    }

    public function login()
    {
        $login = new login();

        $post = $this->request->getPost();
        if (!empty($post)) {
            if ($login->checkLogin($post)) {
                $row = $login->getUserData($post['txtUser']);
                if ($row->ACTIVATION == 0) {
                    $mensagem = [
                        'mensagem' => 'Por favor ative sua conta para acessar!',
                        'tipo' => 'alert-danger',
                    ];
                    $this->session->setFlashdata('mensagem', $mensagem);
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
                    $mensagem = [
                        'mensagem' => 'Bem vindo',
                        'tipo' => 'alert-success',
                    ];
                    $mensagem['mensagem'] = 'Bem vindo ' . $_SESSION['NAME'] . '!';
                    $this->session->setFlashdata('mensagem', $mensagem);
                    return redirect()->to(base_url('/'));
                }
            } else {
                $mensagem['mensagem'] = 'Usuário ou senha inválido';
                $mensagem['tipo'] = 'alert-danger';
                $this->session->setFlashdata('mensagem', $mensagem);
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
