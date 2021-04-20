<?php

namespace app\modules\auth\components\permission\models\base;

use Yii;

/**
 * This is the model class for table "BANCOLOMBIAPCRC.MODEL_HAS_PERMISSIONS".
*
    * @property integer $permission_id
    * @property string $model_type
    * @property integer $model_id
*/
class ModelHasPermissionsBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'BANCOLOMBIAPCRC.MODEL_HAS_PERMISSIONS';
}

/**
* @inheritdoc
*/
public function rules()
{
    $modelId = 'model_id';
    $permissionId = 'permission_id';
    $modelType = 'model_type';
    $targetAttribute = 'targetAttribute';
        return [
            [[$permissionId, $modelType, $modelId], 'required'],
            [[$permissionId, $modelId], 'integer'],
            [[$modelType], 'string', 'max' => 255],
            [[$permissionId, $modelId, $modelType], 'unique', $targetAttribute => [$permissionId, $modelId, $modelType]],
            [[$permissionId, $modelType, $modelId], 'unique', $targetAttribute => [$permissionId, $modelType, $modelId]],
            [[$permissionId], 'exist', 'skipOnError' => true, 'targetClass' => \app\modules\auth\models\Permissions::className(), $targetAttribute => [$permissionId => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'permission_id' => 'Permission ID',
    'model_type' => 'Model Type',
    'model_id' => 'Model ID',
];
}
}
