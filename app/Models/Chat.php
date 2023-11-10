<?php

namespace App\Models;

use CodeIgniter\Model;

class Chat extends Model
{
    protected $table            = 'chat';
    protected $primaryKey       = 'CHAT_ID';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['DATETIME_CREATED', 'DATETIME_UPDATED', 'CHAT_INFOS'];

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    private $messagesModel;

    public function __construct()
    {
        $this->messagesModel = new \App\Models\Messages();
    }

    public function addChat($data)
    {
        return $this->save($data);
    }

    public function saveMessage($data)
    {
        $messageData = [
            'USER_ID'           => $data['USER_ID'],
            'MESSAGE'           => $data['MESSAGE'],
            'DATETIME_CREATED'  => date("Y-m-d H:i:s")
        ];
        $this->messagesModel->saveMessage($messageData);

        $chatData = [
            'USER_ID'           => $data['USER_ID'],
            'MESSAGE_ID'        => $this->messagesModel->getLastMessageByUserId($data['USER_ID']),
            'DATETIME_CREATED'  => date("Y-m-d H:i:s")
        ];
        return $this->save($chatData);
    }
}
