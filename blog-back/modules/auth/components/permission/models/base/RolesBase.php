<?php

namespace app\modules\auth\components\permission\models\base;

use Yii;

/**
 * This is the model class for table "BANCOLOMBIAPCRC.ROLES".
*
    * @property integer $id
    * @property string $name
    * @property string $guard_name
    * @property string $created_at
    * @property string $updated_at
*/
class RolesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'BANCOLOMBIAPCRC.ROLES';
}

/**
* @inheritdoc
*/
public function rules()
{   
    $guardName = 'guard_name';
        return [
            [['name', $guardName], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', $guardName], 'string', 'max' => 255],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}
}