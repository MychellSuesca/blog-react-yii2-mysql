<?php

namespace app\components;

use app\components\traits\Rest;
use yii\rest\ActiveController;
use Yii;

class CustomActiveController extends ActiveController
{
    use Rest;

    /**
     * VerbFilter is an action filter that filters by HTTP request methods.
     * It allows to define allowed HTTP request methods for each action and will throw an HTTP 405 error when the method is not allowed.
     */
    protected function verbs()
    {
        $options = "OPTIONS";
        return [
            'index' => ['GET', 'HEAD', $options],
            'view' => ['GET', 'HEAD', $options],
            'create' => ['POST', $options],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * This property defines the allowed request methods for each action. 
     * For each action that should only support limited set of request methods you add an entry with the action id as array key and an array of allowed methods (e.g. GET, HEAD, PUT) as the value. 
     * If an action is not listed all request methods are considered allowed.
     * You can use '*' to stand for all actions. When an action is explicitly specified, it takes precedence over the specification given by '*'.
    */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    
    /** Prepare HTTPS GET 
     * to show DataProvider 
    */
    public function prepareDataProvider()
    {
        $modelName = (new \ReflectionClass($this->searchModel))->getShortName();

        $searchModel = new $this->searchModel;

        return $searchModel->search([$modelName => \Yii::$app->request->queryParams]);
    }
}
