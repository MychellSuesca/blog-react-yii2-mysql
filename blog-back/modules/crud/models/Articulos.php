<?php

namespace app\modules\crud\models;

class Articulos extends \app\modules\crud\models\base\ArticulosBase
{
	public $cat_id;
    public $likes;
    
    /**
     * This function after find records to format rows
     */
    public function afterFind()
    {
    	$categorias = Categorias::findOne($this->id_categoria);
        if($categorias){
            $this->cat_id = ["value" => $categorias->id, "label" => $categorias->categoria];
        }

        $this->likes = Likes::find()->where(['id_articulo' => $this->id])->count();

        return parent::afterFind();
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'cat_id';
        $fields[] = 'likes';
        return $fields;
    }
}