<?php

namespace App\Controllers;

use App\Models\Todo;

class Main extends BaseController
{

    public function index()
    {
        $job = new Todo();
        if ($this->session->has('USER_ID')) {
        if ($this->request->getGet('search')) {
            $searchInput = $this->request->getGet('search');
            $data = [
                'jobs'      => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
                                   ->like('JOB', $searchInput)
                                   ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                                   ->orderBy('ID_JOB')
                                   ->paginate(10),
                'pager'     => $job->pager,
                'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
                                   ->like('JOB', $searchInput)
                                   ->orLike('jobs.DATETIME_CREATED', $searchInput = implode('-', array_reverse(explode('/', $searchInput))))
                                   ->countAllResults(),
                'search'     => true,
              
            ];
            return view('home', $data);
        }
        
        $data = [
            'jobs'      => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->orderBy('ID_JOB')->paginate(10),
            'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->countAllResults(),
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

    public function done(){
        $job = new Todo();
        $searchInput = $this->request->getGet('search'); 
        if ($searchInput) {
            $data = [
                'jobs'      => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
                                   ->like('JOB', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                                   ->orLike('jobs.DATETIME_CREATED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                                   ->orLike('jobs.DATETIME_FINISHED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                                   ->orderBy('ID_JOB')
                                   ->paginate(10),
                'pager'     => $job->pager,
                'alljobs'   => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')
                                   ->like('JOB', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                                   ->orLike('jobs.DATETIME_CREATED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                                   ->orLike('jobs.DATETIME_FINISHED', $searchInput)->where('jobs.DATETIME_FINISHED !=', NULL)
                                   ->countAllResults(),
                'done'      => true,
                'search'    => true,
            ];
            return view('home', $data);
        }
        $data = [
            'jobs'    => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.DATETIME_FINISHED !=', NULL)->paginate(10),
            'pager'   => $job->pager,
            'alljobs' => $job->select('*')->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.DATETIME_FINISHED !=', NULL)->countAllResults(),
            'done'    => true,
        ];
        return view('home', $data);
    }

}
