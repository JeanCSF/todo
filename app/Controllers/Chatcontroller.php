<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Chatcontroller extends BaseController
{
    public function index()
    {
        if (isset($_SESSION['USER_ID'])) {
            $data = [
                'pageTitle'     => "Chat",
            ];
            return view('chat/chat_index', $data);
        } else {
            $this->main();
        }
        
    }
}
