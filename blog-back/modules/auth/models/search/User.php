<?php

namespace app\modules\auth\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\auth\models\User as UserModel;

/**
 * User represents the model behind the search form of `app\modules\auth\models\User`.
 */
class User extends UserModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'celular', 'tipo_usuario'], 'integer'],
            [['nombre', 'email', 'password', 'fecha_creacion', 'fecha_actualizacion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(isset($params['id'])){
            // grid filtering conditions
            $query->andFilterWhere(['id' => $params['id']]); 
        }
        
        return $dataProvider;
    }
}
