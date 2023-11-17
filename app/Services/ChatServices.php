<?php

namespace App\Services;

use App\Libraries\TimeElapsedStringService;
use App\Models\Chat;
use App\Models\ChatConnections;
use App\Models\Messages;
use App\Models\Users;
use Exception;

class ChatServices
{
    private $TimeElapsedString;
    private $chatModel;
    private $chatConnModel;
    private $messagesModel;
    private $usersModel;
    public function __construct()
    {
        $this->TimeElapsedString = new TimeElapsedStringService();

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
                    'profile_pic' => $chat->TARGET_USER_ID != session('USER_ID') ? $this->usersModel->find($chat->TARGET_USER_ID)->PROFILE_PIC : $this->usersModel->find($chat->USER_ID)->PROFILE_PIC,
                    'user' => $chat->TARGET_USER_ID != session('USER_ID') ? $this->usersModel->find($chat->TARGET_USER_ID)->USER : $this->usersModel->find($chat->USER_ID)->USER,
                    'name' => $chat->TARGET_USER_ID != session('USER_ID') ? $this->usersModel->find($chat->TARGET_USER_ID)->NAME : $this->usersModel->find($chat->USER_ID)->NAME,
                    'last_message' => $this->messagesModel->getLastMessage($chat->CHAT_ID)->MESSAGE,
                    'last_message_id' => $this->messagesModel->getLastMessage($chat->CHAT_ID)->MESSAGE_ID,
                    'last_message_user_id' => $this->messagesModel->getLastMessage($chat->CHAT_ID)->USER_ID,
                    'time_elapsed_last_message' => $this->TimeElapsedString->time_elapsed_string($this->messagesModel->getLastMessage($chat->CHAT_ID)->DATETIME_CREATED),
                    'full_datetime_last_message' => date("d/m/Y H:i:s", strtotime($this->messagesModel->getLastMessage($chat->CHAT_ID)->DATETIME_CREATED)),
                    'timestamp' => date('Y-m-d H:i:s'),
                ];
            }

            return $user_chats;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getFormatedMessages($chatId)
    {
        try {
            $messages = $this->messagesModel->where('CHAT_ID', $chatId)->findAll();
            foreach ($messages as $message) {
                $chat_messages[] = [
                    'message_id' => $message->MESSAGE_ID,
                    'user_id' => $message->USER_ID,
                    'message' => $message->MESSAGE,
                    'time_elapsed_created' => $this->TimeElapsedString->time_elapsed_string($message->DATETIME_CREATED),
                    'full_datetime_created' => date("d/m/Y H:i:s", strtotime($message->DATETIME_CREATED)),
                    'time_elapsed_updated' => isset($message->DATETIME_UPDATED) ? $this->TimeElapsedString->time_elapsed_string($message->DATETIME_UPDATED) : "",
                    'full_datetime_updated' => isset($message->DATETIME_UPDATED) ? date("d/m/Y H:i:s", strtotime($message->DATETIME_UPDATED)) : "",
                    'time_elapsed_read' => isset($message->DATETIME_READ) ? $this->TimeElapsedString->time_elapsed_string($message->DATETIME_READ) : "",
                    'full_datetime_read' => isset($message->DATETIME_READ) ? date("d/m/Y H:i:s", strtotime($message->DATETIME_READ)) : "",

                ];
            }

            return $chat_messages;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getFormatedLastChatMessage($chatId)
    {
        try {
                $last_message[] = [
                    'message_id' => $this->messagesModel->getLastMessage($chatId)->MESSAGE_ID,
                    'user_id' => $this->messagesModel->getLastMessage($chatId)->USER_ID,
                    'message' => $this->messagesModel->getLastMessage($chatId)->MESSAGE,
                    'time_elapsed_created' => $this->TimeElapsedString->time_elapsed_string($this->messagesModel->getLastMessage($chatId)->DATETIME_CREATED),
                    'full_datetime_created' => date("d/m/Y H:i:s", strtotime($this->messagesModel->getLastMessage($chatId)->DATETIME_CREATED)),
                    'time_elapsed_updated' => isset($this->messagesModel->getLastMessage($chatId)->DATETIME_UPDATED) ? $this->TimeElapsedString->time_elapsed_string($this->messagesModel->getLastMessage($chatId)->DATETIME_UPDATED) : "",
                    'full_datetime_updated' => isset($this->messagesModel->getLastMessage($chatId)->DATETIME_UPDATED) ? date("d/m/Y H:i:s", strtotime($this->messagesModel->getLastMessage($chatId)->DATETIME_UPDATED)) : "",
                    'time_elapsed_read' => isset($this->messagesModel->getLastMessage($chatId)->DATETIME_READ) ? $this->TimeElapsedString->time_elapsed_string($this->messagesModel->getLastMessage($chatId)->DATETIME_READ) : "",
                    'full_datetime_read' => isset($this->messagesModel->getLastMessage($chatId)->DATETIME_READ) ? date("d/m/Y H:i:s", strtotime($this->messagesModel->getLastMessage($chatId)->DATETIME_READ)) : "",

                ];

            return $last_message;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}