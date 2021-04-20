<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord as ActiveRecordBase;

/**
 *
 */
class ActiveRecord extends ActiveRecordBase
{
    /**
     * This function before validate to save the record
     */
    public function beforeValidate()
    {
        $clase = new \ReflectionClass($this);
        if ($clase->getNamespaceName() == "app\\models\\search" || $clase->getNamespaceName() == "app\\modules\\logs\\models") {
            return true;
        }

        if ($this instanceof \app\components\interfaces\Search) {
            return true;
        }

        foreach ($this->attributes as $key => $value) {
            if (is_array($value)) {
                $this->$key = (string) @$value["value"];
            } else if ($this->$key) {
                $this->$key = (string) $this->$key;
            }
        }

        return parent::beforeValidate();
    }

    /**
     * Custom set the attributes of model to save
     * @param $data array simple
     */
    public function setCustomAttributes($data)
    {
        foreach ($this->attributes as $attribute => $value) {
            if (isset($data[$attribute])) {
                $this->$attribute = $data[$attribute];
            }
        }
    }
}
