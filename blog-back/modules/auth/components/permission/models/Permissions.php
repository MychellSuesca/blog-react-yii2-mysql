<?php

namespace app\modules\auth\components\permission\models;

use app\modules\auth\components\permission\traits\HasRoles;

class Permissions extends base\PermissionsBase
{

    use HasRoles;

    public function getRoles($arrayWhere = []) {
        $relation = $this->hasMany(Roles::className(), ['id' => 'role_id'])
          ->viaTable(RoleHasPermissions::tableName(), ['permission_id' => 'id']);

        if (count($arrayWhere)) {
            foreach ($arrayWhere as $key => $value) {
                $relation->where("{$key} = :{$key}", [":$key" => $value]);
            }
        }

        return $relation;
    }

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
}

