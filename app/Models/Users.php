<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table            = 'login';
    protected $primaryKey       = 'USER_ID';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['USER', 'PASS', 'NAME', 'EMAIL', 'SU', 'ACTIVATION', 'ACTIVATION_KEY', 'DATETIME_CREATED'];



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

    public function getUser($id)
    {
        $result = $this->find($id);
        return $result;
    }

    public function getAll()
    {
        return $this->findAll();
    }

    public function addUser($post)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $data = [
            'USER'                  => $post['user'],
            'PASS'                  => password_hash($post['pass'], PASSWORD_BCRYPT),
            'NAME'                  => $post['name'],
            'EMAIL'                 => $post['email'],
            'DATETIME_CREATED'      => date("Y-m-d H:i:s"),
        ];
        return $this->save($data) ? true : false;
    }

    public function deleteUser($post)
    {
        if (!empty($post)) {
            return $this->table('login')->where('USER_ID', $post['id'])->delete() ? true : false;
        }
    }
}
