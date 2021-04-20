<?php

namespace app\modules\auth\controllers;

use app\components\RestController;
use app\modules\auth\models\User;
use \yii\web\HttpException;
use Yii;

class LoginController extends RestController
{

    /**
     * public actions, no validate jwt token
     * @attribute array
     */
    protected $optionalAction = [
        'index',
    ];

    /**
     * @return \yii\web\Response
     * Validates if login user attempt is auhtorized
     * Loging Log added
     */
    public function actionIndex()
    {
        $loginData = Yii::$app->getRequest()->getBodyParams();
        
        //validate if params are empty 
        if(empty($loginData['email']) || empty($loginData['encriptPwd'])){
            throw new HttpException(403, "Parametros requeridos ausentes" );
        }

        $data = User::find()->where(['email' => $loginData['email'], 'password' => $loginData['encriptPwd']])->one();

        if(!$data){
            throw new HttpException(403, "El usuario no se encuentra registrado." );
        }

        return [
            "token" => $data->generateJwtToken(),
            "isAdmin" => (($data->tipo_usuario['value'] == 1) ? true : false),
            "user" => $data->id,
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function getUser($model)
    {
        $user = Yii::$app->user->identity;
        return $this->asJson($user);
    }
}
