<?php

namespace App\Models;

use CodeIgniter\Model;

class Login extends Model
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

    public function signUpCreateAccount($post)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $data = [
            'USER'              => $post['user'],
            'PASS'              => password_hash($post['pass'], PASSWORD_BCRYPT),
            'NAME'              => $post['name'],
            'EMAIL'             => $post['email'],
            'ACTIVATION_KEY'    => base64_encode($post['email'] . date('Y-m-d H:i:s')),
            'SU'                => 0,
            'ACTIVATION'        => 0,
            'DATETIME_CREATED'  => date('Y-m-d H:i:s'),

        ];

        return $this->insert($data) ? true : false;
    }

    public function signUpCheckUser($post)
    {
        $user = $post['user'];
        $email = $post['email'];
        $where = "USER = {$user} OR EMAIL = {$email}";
        $query = $this->select('login')
            ->where($where)
            ->countAll();


        return $query ? true : false;
    }

    public function checkLogin($post)
    {

        $user = $post['user'];
        $pass = $post['pass'];
        $query = $this->select('*')
            ->where('USER', $user)->get();
        $row = $query->getRow();
        $hash = $row->PASS;

        return (password_verify($pass, $hash)) ? true : false;
    }

    public function getUserData($user)
    {
        $data = $this->where('USER', $user)->get();
        $userData = $data->getRow();
        return $userData;
    }

    public function getUserIdByKey($key)
    {
        $data = $this->select('USER_ID')->where('ACTIVATION_KEY', $key)->get();
        $id = $data->getrow();
        return $id;
    }

    public function activateUser($params)
    {
        return $this->query("UPDATE `login` SET `ACTIVATION` = 1, ACTIVATION_KEY = :ACTIVATION_KEY: WHERE USER_ID = :USER_ID:", $params) ? true : false;
    }
}
