<?php

namespace app\modules\auth\components\permission\models;
use app\modules\auth\models\User;


class ModelHasRoles extends base\ModelHasRolesBase
{

    public function getRole($arrayWhere = [])
    {
        $relation =  $this->hasMany(Roles::className(), ['id' => 'role_id']);

        if (count($arrayWhere)) {
            foreach ($arrayWhere as $key => $value) {
                $relation->where("{$key} = :{$key}", [":$key" => $value]);
            }
        }

        return $relation;
    }
}

