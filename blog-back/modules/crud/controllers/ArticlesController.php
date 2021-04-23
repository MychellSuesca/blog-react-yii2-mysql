<?php

namespace app\modules\crud\controllers;

use app\components\RestController;
use app\modules\crud\models\Likes;
use \yii\web\HttpException;
use Yii;

class ArticlesController extends RestController
{
    
    public $modelClass = \app\modules\crud\models\Articulos::class;
    public $searchModel = \app\modules\crud\models\search\Articulos::class;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
    * Show Articles
    * @return array
    */
    public function actionIndex()
    {
        $model = new $this->searchModel;
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        return $model->search($requestParams);
    }

    /**
    * Save Like
    * @param int $idArticulo
    * @param int $idUsuario
    */
    public function actionLike($idArticulo, $idUsuario)
    {
        $model = Likes::find()->where(['id_articulo' => $idArticulo, 'id_usuario' => $idUsuario])->one();
        if(!$model){
            $model = new Likes();
            $model->id_articulo = $idArticulo;
            $model->id_usuario = $idUsuario;
            if(!$model->save()){
               throw new \app\exceptions\HttpSaveException($model); 
            }
        }else{
            $model->delete();
        }
    }

    /**
    * Save Article
    * @param array $data
    * @return array
    */
    public function actionSave($id = null)
    {
        if($id){
            $article = $this->modelClass::find()->where(['id' => $id])->one();
        }else{
            $article = new $this->modelClass();
        }
        $data = Yii::$app->getRequest()->getBodyParams();
        $article->setCustomAttributes($data);
        $article->id_categoria = $data['cat_id']['value'];
        if(!$article->fecha_creacion){
            $article->fecha_creacion = date('Y-m-d h:i:s');
        }
        $article->fecha_actualizacion = date('Y-m-d h:i:s');
        if (!$article->save()) {
            throw new \app\exceptions\HttpSaveException($article);
        }
        return ["save" => true, 'message' => 'Registro guardado con exito!'];
    }

    /**
    * Delete Article
    * @param array $data
    * @return array
    */
    public function actionDelete($id)
    {
        $article = $this->modelClass::findOne($id);
        $article->delete();
        return ["save" => true, 'message' => 'Se ha eliminado el artículo'];
    }
}