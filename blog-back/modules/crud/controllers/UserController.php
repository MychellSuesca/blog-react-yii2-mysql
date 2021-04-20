<?php

namespace app\modules\crud\controllers;

use app\components\RestController;
use app\modules\auth\models\User;
use \yii\web\HttpException;
use Yii;

class UserController extends RestController
{

    /**
    * public actions, no validate jwt token
    * @attribute array
    */
    protected $optionalAction = [
        'save',
    ];
    
    public $modelClass = \app\modules\auth\models\User::class;
    public $searchModel = \app\modules\auth\models\search\User::class;
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
    * Save User
    * @param array $data
    * @return array
    */
    public function actionSave($id = null)
    {   
        $data = Yii::$app->getRequest()->getBodyParams();
        if($id){
            $user = $this->modelClass::findOne($id);
        }else{
            $user = new $this->modelClass();
            //Validate if email is register
            if(User::find()->where(['email' => $data['email']])->one()){
                throw new HttpException(403, "Ya hay un usuario registrado con el email ingresado");
            }
        }

        $user->setCustomAttributes($data);
        if(!$user->fecha_creacion){
            $user->fecha_creacion = date('Y-m-d h:i:s');
        }
        $user->fecha_actualizacion = date('Y-m-d h:i:s');
        if (!$user->save()) {
            throw new \app\exceptions\HttpSaveException($user);
        }
        return ["save" => true, 'message' => 'Registro guardado con exito!'];
    }

    /**
    * Delete User
    * @param int $id
    * @return array
    */
    public function actionDelete($id)
    {
        $article = $this->modelClass::findOne($id);
        $article->delete();
        return ["save" => true, 'message' => 'Se ha eliminado el artículo'];
    }
}