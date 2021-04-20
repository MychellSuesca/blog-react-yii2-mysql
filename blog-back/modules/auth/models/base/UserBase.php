<?php

namespace app\modules\auth\models\base;

use Yii;

/**
 * This is the model class for table "usuarios".
*
    * @property integer $id
    * @property string $nombre
    * @property string $email
    * @property string $password
    * @property integer $celular
    * @property integer $tipo_usuario
    * @property string $fecha_creacion
    * @property string $fecha_actualizacion
*/
class UserBase extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'usuarios';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['celular', 'tipo_usuario'], 'integer'],
            [['fecha_creacion', 'fecha_actualizacion'], 'safe'],
            [['nombre', 'email', 'password'], 'string', 'max' => 100],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'nombre' => 'Nombre',
    'email' => 'Email',
    'password' => 'Password',
    'celular' => 'Celular',
    'tipo_usuario' => 'Tipo Usuario',
    'fecha_creacion' => 'Fecha Creacion',
    'fecha_actualizacion' => 'Fecha Actualizacion',
];
}
}