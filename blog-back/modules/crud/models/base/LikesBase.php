<?php

namespace app\modules\crud\models\base;

use Yii;

/**
 * This is the model class for table "likes".
*
    * @property integer $id
    * @property integer $id_articulo
    * @property integer $id_usuario
*/
class LikesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'likes';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['id_articulo', 'id_usuario'], 'integer'],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'id_articulo' => 'Id Articulo',
    'id_usuario' => 'Id Usuario',
];
}
}