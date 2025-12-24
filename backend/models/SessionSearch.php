<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Session;

/**
 * SessionSearch represents the model behind the search form of `common\models\Session`.
 */
class SessionSearch extends Session
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'film_id'], 'integer'],
            [['session_datetime'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search(array $params, string $formName = null): ActiveDataProvider
    {
        $query = Session::find();

        $query->joinWith(['film']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['session_datetime' => SORT_ASC]],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'session.id' => $this->id,
            'film_id'    => $this->film_id,
            'price'      => $this->price,
        ]);

        $query->andFilterWhere(['like', 'session_datetime', $this->session_datetime]);

        return $dataProvider;
    }
}
