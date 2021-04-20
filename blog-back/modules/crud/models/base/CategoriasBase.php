<?php

namespace app\modules\crud\models\base;

use Yii;

/**
 * This is the model class for table "categorias".
*
    * @property integer $id
    * @property string $categoria
*/
class CategoriasBase extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'categorias';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['categoria'], 'string', 'max' => 50],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'categoria' => 'Categoria',
];
}
}