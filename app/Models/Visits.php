<?php

namespace App\Models;

use CodeIgniter\Model;

class Visits extends Model
{
    protected $table            = 'profile_views';
    protected $primaryKey       = 'VIEW_ID';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['PROFILE_USER_ID', 'VISITOR_ID', 'DATETIME_VISITED'];

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

    public function getInfoIfAlreadyVisitedProfile($profile_user_id, $session_user_id)
    {
        $result = $this->where('PROFILE_USER_ID', $profile_user_id)->where('VISITOR_ID', $session_user_id)->get()->getRow('VIEW_ID');

        return $result;
    }
}
