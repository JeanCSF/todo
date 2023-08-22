<?php

namespace App\Controllers;

use App\Models\Login;

class Logincontroller extends BaseController
{

    public function signUp()
    {
        $email = \Config\Services::email();
        $login = new Login();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $img = $this->request->getFile('userpic');
            if (!$this->validate([
                'userpic'          => 'is_image[userpic]|ext_in[userpic,jpeg,jpg,png]|max_dims[userpic,1920,1080]|max_size[userpic,2048]'
            ], [
                'userpic'  => [
                    'is_image'      => 'Arquivo não é de imagem',
                    'ext_in'        => 'Extensão ' . (!empty($img) ? $img->getExtension() : '') . ' não é suportada',
                    'max_dims'      => 'Resolução máxima é 1920x1080'
                ]
            ])) {
                session()->setFlashdata('errors', $this->validator->getErrors());
            }
            if ($this->checkPass($post)) {
                $data['userData']  = $post;
                $msg['msg'] = 'Senhas digitadas não conferem!';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
                return view('login/signup', $data);
            }
            if ($login->signUpCheckUser($post) != 0) {
                $data['userData']  = $post;
                $msg['msg'] = 'Usuário ou email já cadastrado!';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
                return view('login/signup', $data);
            } else if (!$img->hasMoved()) {
                $foto = $img->getFilename();
                $img_name = '';
                !empty($foto) ? $img_name = $img->getRandomName() : $img_name = NULL;

                $login->signUpCreateAccount($post, $img_name);
                $path = '../../public/assets/img/profiles_pics/' . $post['user'];
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                !empty($foto) ? $img->store($path, $img_name) : PHP_EOL;
                $key = base64_encode($post['email'] . date('Y-m-d H:i:s'));
                $email = \Config\Services::email();
                $config = [
                    'protocol'      => 'smtp',
                    'SMTPHost'      => 'smtp.gmail.com',
                    'SMTPUser'      => 'emailt104@gmail.com',
                    'SMTPPass'      => 'wfieldkgarmiynyw',
                    'SMTPPort'      => '587',
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
                $email->send();
                $msg['msg'] = 'Cadastro criado, acesse seu email para confirmar a conta!';
                $msg['type'] = 'alert-success';
                $this->session->setFlashdata('msg', $msg);
                return redirect()->to(base_url('home'));
            }
        }
        $data['pageTitle'] = "Criar Conta";
        echo view('login/signup', $data);
    }

    public function emailConfirm()
    {
        $key = htmlspecialchars($_GET['key']);
        $login = new Login();
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
        $login = new Login();

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
                        'IMG'           => $row->PROFILE_PIC,
                    ];
                    $this->session->set($data);
                    $msg = [
                        'msg' => 'Bem vindo',
                        'type' => 'alert-success',
                    ];
                    $msg['msg'] = 'Bem vindo ' . $_SESSION['NAME'] . '!';
                    $this->session->setFlashdata('msg', $msg);
                    return redirect()->to(base_url('/home'));
                }
            } else {
                $msg['msg'] = 'Usuário ou senha inválido';
                $msg['type'] = 'alert-danger';
                $this->session->setFlashdata('msg', $msg);
            }
        }
        $data['pageTitle'] = "Login";
        echo view('login/login', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('home'));
    }
}
