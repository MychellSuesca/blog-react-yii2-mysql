<?php

namespace app\components;

/**
 * json response
 */
class JsonResponse
{
    /**
     * @var array message
     */
    private $message;

    private $status;

    public function __construct(int $status, $message)
    {
        http_response_code($status);
        $this->status = $status;
        $this->message = $message;
    }
    
    /**
     * @return json message in HTTPS
     */
    public function message()
    {
        header('content-type: application/json');
        if (is_array($this->message)) {
            if ($this->status >= 200 && $this->status < 300) {
                $this->message['ok'] = 1;
            } else {
                $this->message['ok'] = 0;
            }
            echo \CJSON::encode($this->message);
        }

        if (is_string($this->message)) {
            $this->message = ['message' => $this->message];
            echo $this->message();
        }
        \Yii::app()->end();
    }

    /**
     * instance new JsonResponse objet
     * @param int $status http code response
     * @param string|array message
     * @return JsonResponse object
     */
    public static function init(int $status, $message)
    {
        return new JsonResponse($status, $message);
    }
}

