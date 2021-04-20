<?php

namespace app\modules\crud\models\base;

use Yii;

/**
 * This is the model class for table "articulos".
*
    * @property integer $id
    * @property integer $id_categoria
    * @property string $titulo
    * @property string $slug
    * @property string $texto_corto
    * @property string $texto_largo
    * @property string $imagen
    * @property string $fecha_creacion
    * @property string $fecha_actualizacion
*/
class ArticulosBase extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'articulos';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['id_categoria'], 'integer'],
            [['fecha_creacion', 'fecha_actualizacion'], 'safe'],
            [['titulo', 'slug', 'texto_corto'], 'string', 'max' => 50],
            [['texto_largo'], 'string', 'max' => 5000],
            [['imagen'], 'string', 'max' => 100],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'id_categoria' => 'Id Categoria',
    'titulo' => 'Titulo',
    'slug' => 'Slug',
    'texto_corto' => 'Texto Corto',
    'texto_largo' => 'Texto Largo',
    'imagen' => 'Imagen',
    'fecha_creacion' => 'Fecha Creacion',
    'fecha_actualizacion' => 'Fecha Actualizacion',
];
}
}