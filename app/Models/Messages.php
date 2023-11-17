<?php

namespace App\Models;

use CodeIgniter\Model;

class Messages extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'MESSAGE_ID';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['CHAT_ID', 'USER_ID', 'MESSAGE', 'DATETIME_CREATED', 'DATETIME_UPDATED', 'DATETIME_READ'];

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

    public function getChatMessages($chatId)
    {
        $messages = $this->where('CHAT_ID', $chatId)->get();
        return $messages;
    }

    public function saveMessage($data)
    {
        return $this->save($data);
    }

    public function getLastMessage($chatId)
    {
       return $this->where('CHAT_ID', $chatId)->get()->getLastRow();
    }
}
