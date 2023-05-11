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
                    'jobs'      => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->like('JOB_TITLE', $searchInput)
                        ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                        ->orderBy('ID_JOB')
                        ->paginate(12),
                    'pager'     => $job->pager,
                    'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->like('JOB_TITLE', $searchInput)
                        ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                        ->countAllResults(),
                    'search'     => true,

                ];
                return view('home', $data);
            }

            $data = [
                'jobs'      => $job->select('login.PROFILE_PIC, login.USER, login.USER_ID, jobs.ID_JOB, jobs.USER_ID, jobs.JOB_TITLE, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->orderBy('jobs.DATETIME_CREATED DESC')->paginate(5),
                'alljobs'   => $job->select('login.USER, login.USER_ID, jobs.ID_JOB, jobs.USER_ID, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->countAllResults(),
                'pager'     => $job->pager,

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

    public function done()
    {
        $jobs = new Todo();
        $users = new Users();
        $id = $_SESSION['USER_ID'];
        $searchInput = $this->request->getGet('search');
        if ($searchInput) {
            $data = [
                'jobs'      => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)
                    ->like('JOB', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)->where('jobs.USER_ID', $id)
                    ->orLike('jobs.DATETIME_CREATED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)->where('jobs.USER_ID', $id)
                    ->orLike('jobs.DATETIME_FINISHED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)->where('jobs.USER_ID', $id)
                    ->orderBy('ID_JOB')
                    ->paginate(10),
                'pager'     => $job->pager,
                'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)
                    ->like('JOB', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)->where('jobs.USER_ID', $id)
                    ->orLike('jobs.DATETIME_CREATED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                    ->where('jobs.USER_ID', $id)->orLike('jobs.DATETIME_FINISHED', $searchInput)
                    ->where('jobs.DATETIME_FINISHED !=', NULL)->where('jobs.USER_ID', $id)->countAllResults(),
                'done'      => true,
                'search'    => true,
            ];
            return view('users/profile', $data);
        }
        $data = [
            'userData'              => $users->getUser($id),
            'userTasks'             => $jobs->getUserDoneJobs($id),
            'alltasks'              => $jobs->countAllUserJobs($id),
            'alldone'               => $jobs->countAllUserDoneJobs($id),
            'notdone'               => $jobs->countAllUserNotDoneJobs($id),
            'done'      => true,
            'pager'     => $jobs->pager,
        ];
        return view('users/profile', $data);
    }

    public function about()
    {
        echo view('utils/about');
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
                'SMTPPort'      => '2525',
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
        echo view('utils/contact');
    }
}
