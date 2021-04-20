<?php

namespace app\components;

use yii\rest\Controller;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;

class RestController extends Controller
{
     protected $optionalAction = [];

    /**
     * Behavior is the base class for all behavior classes.
     * A behavior can be used to enhance the functionality of an existing component without modifying its code.
     * In particular, it can "inject" its own methods and properties into the component and make them directly accessible via the component.
     * It can also respond to the events triggered in the component and thus intercept the normal code execution.
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => $this->optionalAction,
        ];

        return $behaviors;
    }

    /**
     * This method is invoked right before an action is executed.
     * The method will trigger the EVENT_BEFORE_ACTION event. 
     * The return value of the method will determine whether the action should continue to run.
     * In case the action should not run, the request should be handled inside of the beforeAction code by either providing the necessary output or redirecting the request. 
     * Otherwise the response will be empty.
     */
    public function beforeAction($action)
    {
        //Headers Cross-Origin Resource Sharing (CORS)
        if (strpos(\Yii::$app->request->hostInfo, 'localhost')) {
            Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', '*');
            Yii::$app->getResponse()->getHeaders()->set("Access-Control-Allow-Headers", "*");
        }
        Yii::$app->getResponse()->getHeaders()->set("Strict-Transport-Security", "*");
        Yii::$app->getResponse()->getHeaders()->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS, PUT, DELETE");
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->end();
        }

        //$this->desencriptPOST();

        parent::beforeAction($action);
        return true;
    }

    /**
     * This method desencript the JSON params if is HTTP POST
     */
    private function desencriptPOST()
    {

        if (Yii::$app->getRequest()->getMethod() == 'POST') {
            $encript = Yii::$app->getRequest()->getBodyParams();
            $data = base64_decode($encript['payload']);
            Yii::$app->getRequest()->setBodyParams((array) json_decode($data));
        }
    }

    public function arrayMap($callback, $array)
    {
        $return = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return[$key] = $this->arrayMap($callback, $value);
            } else {
                $return[$key] = call_user_func($callback, $value);
            }
        }

        return $return;
    }
}
