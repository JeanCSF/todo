<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Models\Users;

class Main extends BaseController
{
    public function __construct()
    {
        $session = session();
        if ($session->has('USER_ID')) {
            redirect()->to(base_url('/'));
        } else {
            echo view('main');
        }
    }


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
                'jobs'      => $job->select('login.PROFILE_PIC, login.USER, login.USER_ID, jobs.ID_JOB, jobs.USER_ID, jobs.JOB_TITLE, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED, jobs.PRIVACY')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->orderBy('jobs.DATETIME_CREATED DESC')->paginate(5),
                'alljobs'   => $job->select('jobs.ID_JOB')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->countAllResults(),
                'pageTitle'     => "Página Inicial",

            ];

            echo view('home', $data);
        } else {
            $this->home();
        }
    }

    public function getPosts()
    {
        // $request = service('request');
        // $postData = $request->getPost();
        // $start = $postData['start'];

        $job = new Todo();
        $records =
            $job->select('login.PROFILE_PIC, login.USER, login.USER_ID, jobs.ID_JOB, jobs.USER_ID, jobs.JOB_TITLE, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED, jobs.PRIVACY')
            ->join('login', 'login.USER_ID = jobs.USER_ID')
            ->where('jobs.PRIVACY', true)
            ->orderBy('jobs.DATETIME_CREATED DESC')->findAll($this->rowperpage, 0);
        $html = "";
        foreach ($records as $record) {
            $userId         = $record->USER_ID;
            $userImg        = $record->PROFILE_PIC;
            $user           = $record->USER;
            $created_at     = $record->DATETIME_CREATED;
            $finished_at    = $record->DATETIME_FINISHED;
            $job_title      = $record->JOB_TITLE;
            $id_job         = $record->ID_JOB;
            $job_privacy    = $record->PRIVACY;
            $job_desc       = $record->JOB;

            $html .= `
            <div class="row d-flex justify-content-between post">
            <div class="d-flex justify-content-between">
                <p>
                    <a style="text-decoration: none;" href="` . base_url('userscontroller/profile/' . base64_encode($userId)) . `" class="link-secondary fs-4">
                        <img class="rounded-circle border border-light-subtle" height="64" width="64" src="` . !empty($userImg) ? base_url('../../assets/img/profiles_pics/' . $user . '/' . $userImg) : base_url('/assets/logo.png') . `" alt=""> <br> ` . $user . `
                    </a>
                    <br>
                    <span style="font-size: 12px;" class="fst-italic text-muted">` . date("d/m/Y", strtotime($created_at)) . `</span>
                </p>
                <p ` . !empty($finished_at) ? "style='text-decoration: line-through;'" : ""  . ` class="fs-3"><?= $job_title ?></p>
                <?php if (` . $_SESSION['USER_ID'] == $userId . `) : ?>
                    <div class="dropdown">
                        <button class="nav-link bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis"></i>
                        </button>
                        <ul class="dropdown-menu post-it-dropdown">
                            <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy('$id_job')">Privacidade ` . ($job_privacy == 1) ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>' . `</a></li>  
                            <?php if (` . empty($finished_at) . `) : ?>
                                <li><a class="dropdown-item" href="` . site_url('todocontroller/jobdone/' . $id_job) . `" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit($id_job, $job_title, $job_desc)">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete($id_job)">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <p> </p>
                <?php endif; ?>
            </div>
            <p class="p-2">` . $job_desc . `
            <p>
                <?php if (` . !empty($finished_at) . `) : ?>
            <div class="text-end">
                <p>Finalizada - ` . date("d/m/Y", strtotime($finished_at)) . ` ?> <i class='fa fa-check-double'></i></p>
            </div>
        <?php endif; ?>
        </div>
            `;
        }
        // New CSRF token
        $data['token'] = csrf_hash();

        // Fetch data
        $data['html'] = $html;

        return $this->response->setJSON($data);
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
        $data['pageTitle'] = "Sobre o Projeto";
        echo view('utils/about', $data);
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
