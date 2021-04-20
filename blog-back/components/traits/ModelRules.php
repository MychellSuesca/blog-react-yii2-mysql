<?php

namespace app\components\traits;

trait ModelRules
{

    /**
     * Validate if insert value is exist
     * @return
    */
    public function unique($att)
    {
        if ($this->getScenario() === 'insert' && $this->find("$att = :att", ['att' => $this->$att])) {
            $this->addError($att, 'El valor ingresado, ya existe en la base de datos.');
        }
    }

    /**
     * Validate if number start by one
     * @param $att atribute to validate
    */
    public function phoneStartedByOne($att)
    {
        if (!empty($this->$att) && substr($this->$att, 0, 1) == '1') {
            $this->addError($att, 'Los telefonos no deben comenzar por 1');
        }
    }

    /**
     * Validate if number started by three
     * @param $att atribute to validate
    */
    public function mobileStartedByThree($att)
    {
        if (!empty($this->$att) && substr($this->$att, 0, 1) !== '3') {
            $this->addError($att, 'El numero debe comenzar por 3');
        }
    }

    /**
     * Validate phones if is 10 caracters or 7 caracters
     * @param $att atribute to validate
    */
    public function mobileAndphoneStartedByThreeAndOne($att)
    {
        if (!empty($this->$att)) {
            if (strlen($this->$att) == 10 && substr($this->$att, 0, 1) !== '3') {
                $this->addError($att, 'El numero debe comenzar por 3');
            } else if (strlen($this->$att) == 7 && substr($this->$att, 0, 1) === '1') {
                $this->addError($att, 'Los telefonos no deben comenzar por 1');
            }
        }
    }

    /**
     * Validate if insert value is exist
     * @param $att atribute to validate
    */
    public function mobileStartedByThreeMaxTenCharacters($att)
    {
        if (strlen($this->$att) == 10 && !empty($this->$att) && substr($this->$att, 0, 1) == '1') {
            $this->addError($att, 'El numero no debe comenzar por 1');
        }
    }

    /**
     * Valid if one field are empty
     * @param $att atribute to validate
     * @param $params aditional params to define rule
    */
    public function validateRequiredOne($att, $params)
    {
        $error = true;
        $campos = [];
        $attributeLabels = $this->attributeLabels();
        foreach ($params['fields'] as $field) {
            $campos[] = $attributeLabels[$field];
            if ($this->$field) {
                $error = false;
            }
        }

        if ($error) {
            $this->addError($att, 'Es obligatorio al menos uno de los campos (' . implode(', ', $campos) . ')');
        }
    }

    /**
     * Validate if the fields are identical
     * @param $att atribute to validate
     * @param $params aditional params to define rule
    */
    public function CompareAddress($att, $params)
    {
        $field = $params['fields'];
        if (!empty($this->$att) && !empty($this->$field) && $this->$att !== $this->$field) {
            $this->addError($att, 'Este campo no es igual');
        }
    }

    /**
     * Validate if is required
     * @param $att atribute to validate
    */
    public function required($att)
    {
        if (is_null($this->$att)) {
            $this->addError($att, 'Debe de ser obligatorio.');
        }
    }

    /**
     * Valid if the quota is not in multiples of 100
     * @param $att atribute to validate
    */
    public function cupoDiv($att)
    {
        if (!is_null($this->$att)) {
            $cupo = str_replace(".", "", $this->$att);
            if (!is_int($cupo / 1000)) {
                $this->addError($att, 'Debe de ser múltiplo de 1.000');
            }
        }
    }

    /**
     * Valid if apply garanty
     * @param $att atribute to validate
    */
    public function validateIfApplyGaranty($att)
    {
        if (($this->aplica_garantia === 1 || $this->aplica_garantia === '1') && empty($this->$att)) {
            $this->addError($att, 'Es obligatorio.');
        }
    }
}
