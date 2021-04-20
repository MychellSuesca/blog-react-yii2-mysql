<?php

namespace app\exceptions;

use Yii;
use yii\helpers\Json;
use yii\web\Response;

/**
 * Handler errors
 */
class Handler extends \yii\base\ErrorHandler
{
    /**
     * Renders the exception.
     * @param \Exception|\Error $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        Yii::$app->getResponse()->getHeaders()->set("Access-Control-Allow-Headers", "*");
        Yii::$app->getResponse()->getHeaders()->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS, PUT, DELETE");
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->end();
        }

        $response = \Yii::$app->response;
        
        // $headers is an object of yii\web\HeaderCollection 
        $headers = Yii::$app->request->headers;
        // returns the Accept header value
        $debug = $headers->has('debug');
        
        if ($debug) {
            print_r($exception);
            die();
        }

        $response->format = Response::FORMAT_JSON;
        $response->statusCode = @$exception->statusCode;
        if ($exception instanceof yii\web\NotFoundHttpException) {
            $response->content = Json::encode(['message' => 'Página no encontrada.']);
        } elseif ($exception instanceof \yii\web\UnauthorizedHttpException) {
            $response->content = Json::encode(['message' => 'Credenciales inválidas y/o token expirado.']);
        } elseif ($exception instanceof \yii\web\HttpException) {
            $response->content = Json::encode(['message' => $exception->getMessage()]);
        } elseif ($exception instanceof \yii\web\BadRequestHttpException) {
            $response->content = Json::encode(['message' => 'Parámetros requeridos ausentes.']);
        } elseif ($exception instanceof \app\exceptions\HttpSaveException) {
            $response->content = Json::encode(['message' => $exception->getMessage(), "model" => $exception->getClassName(), "errors" => $exception->getErrorMessage(), "tab" => $exception->tab]);
        } elseif (!$response->statusCode || $response->statusCode == 500 || $response->statusCode == 200) {
            $response->statusCode = 451;
            $response->content = Json::encode(['message' => 'Contacte con el administrador!']);
        } else {
            $response->content = Json::encode(['message' => $exception->getMessage()]);
        }

        $response->send();
    }
}
