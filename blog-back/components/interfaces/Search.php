<?php

namespace app\components\interfaces;

interface Search
{
    public function rules();
    public function scenarios();
    public function search($params);
}
