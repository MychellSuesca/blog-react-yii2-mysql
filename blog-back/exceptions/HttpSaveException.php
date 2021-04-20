<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\exceptions;

use yii\base\UserException;

/**
 * HttpSaveException represents an exception caused by an improper request of the end-user.
 *
 * HttpSaveException can be differentiated via its [[statusCode]] property value which
 * keeps a standard HTTP status code (e.g. 404, 500). Error handlers may use this status code
 * to decide how to format the error page.
 *
 * Throwing an HttpSaveException like in the following example will result in the 404 page to be displayed.
 *
 * ```php
 * if ($item === null) { // item does not exist
 *     throw new \yii\web\HttpSaveException(404, 'The requested Item could not be found.');
 * }
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HttpSaveException extends UserException
{
    /**
     * @var int HTTP status code, such as 403, 404, 500, etc.
     */
    public $tab;
    public $statusCode;
    private $model;

    /**
     * Constructor.
     * @param int $status HTTP status code, such as 404, 500, etc.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($model = null, $tab = null, $code = 0, \Exception $previous = null)
    {
        $this->statusCode = 422;
        $this->tab = $tab;
        $this->model = $model;
        parent::__construct("Error al guardar!", $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        if (isset(Response::$httpStatuses[$this->statusCode])) {
            return Response::$httpStatuses[$this->statusCode];
        }

        return 'Error';
    }

    public function getErrorMessage()
    {
        return $this->model->getErrors();
    }

    public function getClassName()
    {
        $clase = new \ReflectionClass($this->model);
        return $clase->getShortName();
    }
}
