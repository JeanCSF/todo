<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Models\Users;

class Main extends BaseController
{

    public function index()
    {
        $job = new Todo();
        if ($this->session->has('USER_ID')) {
            $id = $_SESSION['USER_ID'];
            if ($this->request->getGet('search')) {
                $searchInput = $this->request->getGet('search');
                $data = [
                    'jobs'          => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->like('JOB_TITLE', $searchInput)
                        ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                        ->orderBy('jobs.DATETIME_CREATED')
                        ->paginate(12),
                    'pager'         => $job->pager,
                    'alljobs'       => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->like('JOB_TITLE', $searchInput)
                        ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                        ->countAllResults(),
                    'search'        => true,
                    'pageTitle'     => "Pesquisa",
                    'searchInput'   => $searchInput,


                ];
                return view('home', $data);
            }

            $data = [
                'jobs'          => $job->select('login.PROFILE_PIC, login.USER, login.USER_ID, jobs.ID_JOB, jobs.USER_ID, jobs.JOB_TITLE, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED, jobs.PRIVACY')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->orderBy('jobs.DATETIME_CREATED DESC')->paginate(5),
                'alljobs'       => $job->select('jobs.ID_JOB')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->countAllResults(),
                'pageTitle'     => "Página Inicial",

            ];

            echo view('home', $data);
        } else {
            $this->home();
        }
    }

    public function home()
    {
        $session = session();
        if ($session->has('USER_ID')) {
            redirect()->to(base_url('/'));
        } else {
            echo view('main');
        }
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
                'SMTPHost'      => 'smtp.gmail.com',
                'SMTPUser'      => 'emailt104@gmail.com',
                'SMTPPass'      => 'wfieldkgarmiynyw',
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
                return redirect()->to(base_url('/'));
            }
        }
        $data['pageTitle'] = "Contato";
        echo view('utils/contact', $data);
    }
}
