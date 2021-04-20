<?php

namespace app\modules\auth\components\permission\models;

use app\modules\auth\components\permission\traits\HasPermissions;

class Roles extends base\RolesBase
{
    use HasPermissions;

    
    /**
    * override parent validate
    * @return boolean
    */

    public function beforeValidate()
    {
        if (!$this->guard_name) {
            $this->guard_name = 'web';
        }
        
        return parent::beforeValidate();
    }

    public function getPermissions() {
        return $this->hasMany(Permissions::className(), ['id' => 'permission_id'])
          ->viaTable(RoleHasPermissions::tableName(), ['role_id' => 'id']);
    }
}

