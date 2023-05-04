<?php

namespace App\Controllers;

use App\Models\Todo;

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
                    'jobs'      => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)
                        ->like('JOB', $searchInput)
                        ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                        ->orderBy('ID_JOB')
                        ->paginate(10),
                    'pager'     => $job->pager,
                    'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)
                        ->like('JOB', $searchInput)
                        ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                        ->countAllResults(),
                    'search'     => true,

                ];
                return view('home', $data);
            }

            $data = [
                'jobs'      => $job->select('login.USER, jobs.ID_JOB, jobs.USER_ID, jobs.JOB, jobs.DATETIME_CREATED, jobs.DATETIME_UPDATED, jobs.DATETIME_FINISHED')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.PRIVACY', true)->orderBy('ID_JOB')->paginate(10),
                'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)->countAllResults(),
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
        $job = new Todo();
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
            return view('home', $data);
        }
        $data = [
            'jobs'    => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
                ->where('jobs.DATETIME_FINISHED !=', NULL)
                ->where('jobs.USER_ID', $id)->paginate(10),
            'pager'   => $job->pager,
            'alljobs' => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
                ->where('jobs.DATETIME_FINISHED !=', NULL)
                ->where('jobs.USER_ID', $id)->countAllResults(),
            'done'    => true,
        ];
        return view('home', $data);
    }
}
