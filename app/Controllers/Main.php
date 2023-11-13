<?php

namespace App\Controllers;

use App\Models\Likes;
use App\Models\Todo;
use App\Models\Users;

class Main extends BaseController
{
    public function checkSession()
    {
        return session('USER_ID');
    }

    public function main()
    {
        if (session('USER_ID')) {
            return redirect()->to(base_url('home'));
        } else {
            return view('main');
        }
    }

    public function index()
    {
        if (!$this->checkSession()) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'pageTitle'     => "Página Inicial",
        ];
        return view('home', $data);
    }

    public function about()
    {
        $data['pageTitle'] = "Sobre o Projeto";
        return view('utils/about', $data);
    }

    public function contact()
    {
        $post = $this->request->getPost();
        if (!empty($post)) {
            $email = \Config\Services::email();

            $config = [
                'protocol'      => 'smtp',
                'SMTPHost'      => 'sandbox.smtp.mailtrap.io',
                'SMTPUser'      => '6a29d381cdc759',
                'SMTPPass'      => '60e1758b41e608',
                'SMTPPort'      => '587',
                'mailType'      => 'html'
            ];

            $email->initialize($config);
            $email->setFrom($post['contactEmail']);
            $email->setSubject('Novo feedback da aplicação (TODOLIST)');
            $email->setTo('jean.csf.17@gmail.com');

            $email->setMessage($post['contactText']);

            if ($email->send()) {
                $msg['msg'] = 'Feedback enviado com sucesso, muito obrigado! ;)';
                $msg['type'] = 'alert-success';
                $this->session->setFlashdata('msg', $msg);
                return redirect()->to(base_url('/home'));
            }
        }
        $data['pageTitle'] = "Contato";
        echo view('utils/contact', $data);
    }
}
