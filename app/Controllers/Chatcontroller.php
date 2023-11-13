<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Main;

use App\Models\Chat;
use App\Models\Messages;
use App\Models\Users;

class ChatController extends BaseController
{

    private $mainController;

    private $chatModel;
    private $messagesModel;
    private $usersModel;

    public function __construct()
    {
        $this->mainController = new Main();

        $this->chatModel = new Chat();
        $this->messagesModel = new Messages();
        $this->usersModel = new Users();
    }

    public function index()
    {
        if (!$this->mainController->checkSession()) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'pageTitle' => "Mensagens",
        ];
        return view('chat/chat_index', $data);
    }

    public function createChat()
    {
        if (!$this->mainController->checkSession()) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }

        $data = [
            'DATETIME_CREATED'  => date("Y-m-d H:i:s"),
        ];

        try {
            $this->chatModel->addChat($data);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao criar chat: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno do servidor']);
        }
    }

    public function chat($user, $chatId)
    {
        if (!$this->mainController->checkSession()) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'pageTitle' => ($user == session('USER') ? "Mensagens {$user}" : "{$user}"),
            'user'      => $user,
        ];
        return view('chat/chat_index', $data);
    }

    public function sendMessage()
    {
        if (!$this->mainController->checkSession()) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }

        $chatId = $this->chatModel->addChat(['DATETIME_CREATED' => date("Y-m-d H:i:s")]);

        $userIds = [session('USER_ID'), $this->usersModel->where('USER', $this->request->getVar('chat_user_name'))->get()->getRow('USER_ID')];
        foreach ($userIds as $userId) {
            $this->chatModel->addUserToChat($chatId, $userId);
        }

        $data = [
            'CHAT_ID'           => $chatId,
            'USER_ID'           => session('USER_ID'),
            'MESSAGE'           => $this->request->getVar('message'),
            'DATETIME_CREATED'  => date("Y-m-d H:i:s")
        ];

        try {

            $this->messagesModel->saveMessage($data);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao salvar mensagem: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno do servidor']);
        }
    }

    public function getMessages($chatId)
    {
    }
}
