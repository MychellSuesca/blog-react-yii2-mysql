<?php

namespace app\modules\auth\components\permission\models\base;

use Yii;

/**
 * This is the model class for table "BANCOLOMBIAPCRC.ROLE_HAS_PERMISSIONS".
*
    * @property integer $permission_id
    * @property integer $role_id
*/
class RoleHasPermissionsBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'BANCOLOMBIAPCRC.ROLE_HAS_PERMISSIONS';
}

/**
* @inheritdoc
*/
public function rules()
{   
    $permissionId = 'permission_id';
    $roleId = 'role_id';
    $targetAttribute = 'targetAttribute';
        return [
            [[$permissionId, $roleId], 'required'],
            [[$permissionId, $roleId], 'integer'],
            [[$permissionId, $roleId], 'unique', $targetAttribute => [$permissionId, $roleId]],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'permission_id' => 'Permission ID',
    'role_id' => 'Role ID',
];
}
}
