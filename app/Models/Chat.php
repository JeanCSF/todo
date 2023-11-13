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
        $this->insert($data);
        return $this->insertID();
    }

    public function addUserToChat($chatId, $userId)
    {
        return $this->db->table('CHAT_CONNECTIONS')->insert(['CHAT_ID' => $chatId, 'USER_ID' => $userId]);
    }

    public function getChatId($chatInfos)
    {
        $chatId = $this->select('CHAT_ID')->where('CHAT_INFOS', $chatInfos)->get()->getRow('CHAT_ID');
        return $chatId;
    }
}
