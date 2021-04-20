<?php

namespace app\modules\auth\components\permission\models\base;

use Yii;

/**
 * This is the model class for table "BANCOLOMBIAPCRC.MODEL_HAS_ROLES".
*
    * @property integer $role_id
    * @property string $model_type
    * @property integer $model_id
*/
class ModelHasRolesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'BANCOLOMBIAPCRC.MODEL_HAS_ROLES';
}

/**
* @inheritdoc
*/
public function rules()
{
    $modelId = 'model_id';
    $roleId = 'role_id';
    $modelType = 'model_type';
    $targetAttribute = 'targetAttribute';

        return [
            [[$roleId, $modelType, $modelId], 'required'],
            [[$roleId, $modelId], 'integer'],
            [[$modelType], 'string', 'max' => 255],
            [[$roleId, $modelId, $modelType], 'unique', $targetAttribute => [$roleId, $modelId, $modelType]],
            [[$roleId, $modelType, $modelId], 'unique', $targetAttribute => [$roleId, $modelType, $modelId]],
            //[[$roleId], 'exist', 'skipOnError' => true, 'targetClass' => \app\modules\auth\models\Roles::className(), $targetAttribute => [$roleId => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'role_id' => 'Role ID',
    'model_type' => 'Model Type',
    'model_id' => 'Model ID',
];
}
}
