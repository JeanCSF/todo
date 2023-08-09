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
    protected $allowedFields    = ['USER', 'PASS', 'NAME', 'EMAIL', 'SU', 'ACTIVATION', 'ACTIVATION_KEY', 'DATETIME_CREATED', 'DATETIME_UPDATED', 'PROFILE_PIC'];



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

    public function getUser($user)
    {
        $result = $this->select('*')->where('USER', $user)->get();
        return $result->getResultObject();
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
            'ACTIVATION'            => 1,
        ];
        return $this->save($data) ? true : false;
    }

    public function editUser($id, $post)
    {
        date_default_timezone_set('America/Sao_Paulo');
        if (!empty($post)) {
            $data = [
                'USER'                  => $post['user'],
                'NAME'                  => $post['name'],
                'EMAIL'                 => $post['email'],
                'DATETIME_UPDATED'      => date("Y-m-d H:i:s"),
            ];
            return $this->table('login')->update($id, $data) ? true : false;
        }
    }

    public function deleteUser($post)
    {
        if (!empty($post)) {
            return $this->table('login')->where('USER_ID', $post['id'])->delete() ? true : false;
        }
    }

    public function saveProfilePic($id, $img_name)
    {
        $data = [
            'PROFILE_PIC'           => $img_name,
        ];
        if(!empty ($img_name)){
            return $this->table('login')->update($id,$data) ? true : false;
        }
    }

}
