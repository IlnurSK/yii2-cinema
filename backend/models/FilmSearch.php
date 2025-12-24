<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Film;

/**
 * FilmSearch represents the model behind the search form of `common\models\Film`.
 */
class FilmSearch extends Film
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'duration'], 'integer'],
            [['title', 'image_ext', 'description', 'age_rating'], 'safe'],
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
        $query = Film::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'duration' => $this->duration,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'image_ext', $this->image_ext])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'age_rating', $this->age_rating]);

        return $dataProvider;
    }
}
