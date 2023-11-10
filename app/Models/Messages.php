<?php

namespace App\Models;

use CodeIgniter\Model;

class Messages extends Model
{
    protected $table            = 'chat';
    protected $primaryKey       = 'MESSAGE_ID';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['USER_ID', 'MESSAGE', 'DATETIME_CREATED', 'DATETIME_UPDATED', 'DATETIME_READ'];

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

    public function getLastMessageByUserId($userId)
    {
        $result = $this->select('MESSAGE_ID')->where('USER_ID', $userId)->like('DATETIME_CREATED', date("Y-m-d H:i"))->get();
        return $result->getRow();
    }

    public function saveMessage($data)
    {
        return $this->save($data);
    }
}
