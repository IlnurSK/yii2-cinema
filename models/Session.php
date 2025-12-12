<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property int $id
 * @property int $film_id
 * @property string $session_datetime
 * @property float $price
 *
 * @property Film $film
 */
class Session extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['film_id', 'session_datetime', 'price'], 'required'],
            [['film_id'], 'integer'],
            [['session_datetime'], 'safe'],
            [['price'], 'number'],
            [['film_id'], 'exist', 'skipOnError' => true, 'targetClass' => Film::class, 'targetAttribute' => ['film_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'film_id' => 'Film ID',
            'session_datetime' => 'Session Datetime',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Film]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilm()
    {
        return $this->hasOne(Film::class, ['id' => 'film_id']);
    }

}
