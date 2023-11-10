<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Main;

use App\Models\Chat;
use App\Models\Messages;


class ChatController extends BaseController
{

    private $mainController;

    private $chatModel;
    private $messagesModel;

    public function __construct()
    {
        $this->mainController = new Main();

        $this->chatModel = new Chat();
        $this->messagesModel = new Messages();
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
            'CHAT_INFOS'          => json_encode([

                'session_user_id'   => $this->request->getVar('session_user_id'),
                'chat_user_name'    => $this->request->getVar('chat_user_name')
            ])
        ];

        try {
            $this->chatModel->addChat($data);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao criar chat: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno do servidor']);
        }
    }

    public function chat($user, $chatId = null)
    {
        if (!$this->mainController->checkSession()) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'pageTitle' => ($user == session('USER') ? "Mensagens {$user}" : "{$user}"),
            'user' => $user,
        ];
        return view('chat/chat_index', $data);
    }

    public function sendMessage()
    {
        if (!$this->mainController->checkSession()) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }

        $data = [
            'USER_ID' => session('USER_ID'),
            'MESSAGE' => $this->request->getVar('message')
        ];

        try {
            $this->chatModel->saveMessage($data);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao salvar mensagem: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno do servidor']);
        }
    }
}
