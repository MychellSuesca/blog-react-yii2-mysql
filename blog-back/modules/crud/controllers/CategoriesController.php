<?php

namespace app\modules\crud\controllers;

use app\components\CustomActiveController;
use Yii;

/**
 * ActualizacionDatosController implements the CRUD actions for ActualizacionDatos model.
 */
class CategoriesController extends CustomActiveController
{
    public $modelClass = \app\modules\crud\models\Categorias::class;
    public $searchModel = \app\modules\crud\models\search\Categorias::class;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actionIndex()
	{
	    $model = new $this->searchModel;
	    $requestParams = Yii::$app->getRequest()->getQueryParams();
	    return $model->search($requestParams);
	}

    /**
    * Save Categoria
    * @param array $data
    * @return array
    */
    public function actionSave($id = null)
    {   
        $data = Yii::$app->getRequest()->getBodyParams();
        if($id){
            $categoria = $this->modelClass::findOne($id);
        }else{
            $categoria = new $this->modelClass();
        }

        $categoria->setCustomAttributes($data);
        if (!$categoria->save()) {
            throw new \app\exceptions\HttpSaveException($categoria);
        }
        return ["save" => true, 'message' => 'Registro guardado con exito!'];
    }

    /**
    * Delete Categorie
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
