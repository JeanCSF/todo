<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Models\Users;

class Userscontroller extends BaseController
{

    public function profile($id){
        $id = base64_decode($id);
        $users = new Users();
        $jobs = new Todo();
        $profile = $users->getUser($id);
        $tasks = $jobs->getUserJobs($id);
        $data = [
            'userData'      => $profile,
            'userTasks'     => $tasks,

        ];
        echo view('users/profile', $data);

    }
}
