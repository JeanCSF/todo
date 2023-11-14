<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatConnections extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'chat_connections';
    protected $primaryKey = 'ID';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['CHAT_ID', 'USER_ID', 'TARGET_USER_ID'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function addUserChatConn($chatId, $userId, $targetUserId)
    {
        $data = [
            'CHAT_ID'           => $chatId,
            'USER_ID'           => $userId,
            'TARGET_USER_ID'    => $targetUserId
        ];

        return $this->save($data);
    }

    public function getChatId($userId, $targetUserId)
    {
        return $this->where('USER_ID', $userId)
            ->where('TARGET_USER_ID', $targetUserId)
            ->orWhere('USER_ID', $targetUserId)
            ->where('TARGET_USER_ID', $userId)
            ->get()
            ->getRow('CHAT_ID');
    }

    public function getChatList($userId)
    {
        return $this->where('USER_ID', $userId)->orWhere('TARGET_USER_ID', $userId)->get()->getResult();
    }
}
