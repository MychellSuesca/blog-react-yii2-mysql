<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\rest\UrlRule as UrlRuleBase;

class UrlRule extends UrlRuleBase
{
    public $models = [];
    /**
     * @var string|array the controller ID (e.g. `user`, `post-comment`) that the rules in this composite rule
     * are dealing with. It should be prefixed with the module ID if the controller is within a module (e.g. `admin/user`).
     *
     * By default, the controller ID will be pluralized automatically when it is put in the patterns of the
     * generated rules. If you want to explicitly specify how the controller ID should appear in the patterns,
     * you may use an array with the array key being as the controller ID in the pattern, and the array value
     * the actual controller ID. For example, `['u' => 'user']`.
     *
     * You may also pass multiple controller IDs as an array. If this is the case, this composite rule will
     * generate applicable URL rules for EVERY specified controller. For example, `['user', 'post']`.
     */
    public $controller;
    /**
     * @var bool whether to automatically pluralize the URL names for controllers.
     * If true, a controller ID will appear in plural form in URLs. For example, `user` controller
     * will appear as `users` in URLs.
     * @see controller
     */
    public $pluralize = false;
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (empty($this->controller) || !is_string($this->controller)) {
            throw new InvalidConfigException('"controller" must be set.');
        }

        $controllers = [];
        foreach ((array) $this->models as $model) {
            $controllers["$this->controller-$model"] = $this->controller;
        }
        $this->controller = $controllers;

        parent::init();
    }
}
