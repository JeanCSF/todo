<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatConnections;
use App\Models\Messages;
use App\Models\Users;
use Exception;

class ChatServices
{
    private $chatModel;
    private $chatConnModel;
    private $messagesModel;
    private $usersModel;
    public function __construct()
    {
        $this->chatModel = new Chat();
        $this->chatConnModel = new ChatConnections();
        $this->messagesModel = new Messages();
        $this->usersModel = new Users();

    }

    public function getFormatedChats($userId)
    {
        try {
            $chats = $this->chatConnModel->getChatList($userId);
            foreach ($chats as $chat) {
                $user_chats[] = [
                    'conn_id' => $chat->ID,
                    'chat_id' => $chat->CHAT_ID,
                    'user_id' => $chat->USER_ID,
                    'target_id' => $chat->TARGET_USER_ID,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                $targets_info[] = [
                    'profile_pic'   => $chat->TARGET_USER_ID != session('USER_ID')? $this->usersModel->where('USER_ID', $chat->TARGET_USER_ID)->get()->getRow('PROFILE_PIC') : $this->usersModel->where('USER_ID', $chat->USER_ID)->get()->getRow('PROFILE_PIC'),
                    'user'          => $chat->TARGET_USER_ID != session('USER_ID')? $this->usersModel->where('USER_ID', $chat->TARGET_USER_ID)->get()->getRow('USER') : $this->usersModel->where('USER_ID', $chat->USER_ID)->get()->getRow('USER'),
                    'name'          => $chat->TARGET_USER_ID != session('USER_ID')? $this->usersModel->where('USER_ID', $chat->TARGET_USER_ID)->get()->getRow('NAME') : $this->usersModel->where('USER_ID', $chat->USER_ID)->get()->getRow('NAME'),
                ];
            }

            $response = [
                'chats'     => $user_chats,
                'targets'   => $targets_info
            ];

            return $response;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}