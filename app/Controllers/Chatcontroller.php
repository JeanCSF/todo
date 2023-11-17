<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Main;

use App\Models\Chat;
use App\Models\ChatConnections;
use App\Models\Messages;
use App\Models\Users;
use App\Services\ChatServices;

class ChatController extends BaseController
{

    private $mainController;

    private $chatServices;

    private $chatModel;
    private $chatConnModel;
    private $messagesModel;
    private $usersModel;

    public function __construct()
    {
        $this->mainController = new Main();

        $this->chatServices = new ChatServices();

        $this->chatModel = new Chat();
        $this->chatConnModel = new ChatConnections();
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

    public function chat($user)
    {
        if (!$this->mainController->checkSession()) {
            return redirect()->to(base_url('/'));
        }
        $chatId = $this->request->getVar('chatId');

        $data = [
            'pageTitle' => ($user == session('USER') ? "Mensagens {$user}" : "{$user}"),
            'user' => $user,
            'chatId' => $chatId
        ];
        return view('chat/chat_index', $data);
    }

    public function sendMessage()
    {
        if (!$this->mainController->checkSession()) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }

        $chatingWithId = $this->usersModel->where('USER', $this->request->getVar('chat_user_name'))->get()->getRow('USER_ID');

        $chatId = $this->chatConnModel->getChatId(session('USER_ID'), $chatingWithId);
        if (empty($chatId)) {
            $chatId = $this->chatModel->addChat(['DATETIME_CREATED' => date("Y-m-d H:i:s")]);
            $this->chatConnModel->addUserChatConn($chatId, session('USER_ID'), $chatingWithId);
        }

        $data = [
            'CHAT_ID' => $chatId,
            'USER_ID' => session('USER_ID'),
            'MESSAGE' => $this->request->getVar('message'),
            'DATETIME_CREATED' => date("Y-m-d H:i:s")
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
        if ($this->mainController->checkSession()) {

            $response = $this->chatServices->getFormatedMessages($chatId);
            return $this->response->setStatusCode(200)->setJSON($response);

        } else {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }
    }

    public function getLastChatMessage($chatId)
    {
        if ($this->mainController->checkSession()) {

            $response = $this->chatServices->getFormatedLastChatMessage($chatId);
            return $this->response->setStatusCode(200)->setJSON($response);

        } else {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }
    }

    public function getChats($userId)
    {
        if ($this->mainController->checkSession()) {

            $response = $this->chatServices->getFormatedChats($userId);
            return $this->response->setStatusCode(200)->setJSON($response);

        } else {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Usuário não autenticado']);
        }
    }
}
