<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "film".
 *
 * @property int $id
 * @property string $title Название фильма
 * @property string|null $image_ext Расширение картинки
 * @property string|null $description Описание
 * @property int $duration Продолжительность
 * @property string|null $age_rating Возрастные ограничения
 *
 * @property Session[] $sessions
 */
class Film extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'film';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_ext', 'description', 'age_rating'], 'default', 'value' => null],
            [['title', 'duration'], 'required'],
            [['description'], 'string'],
            [['duration'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['image_ext', 'age_rating'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'image_ext' => 'Image Ext',
            'description' => 'Description',
            'duration' => 'Duration',
            'age_rating' => 'Age Rating',
        ];
    }

    /**
     * Gets query for [[Sessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::class, ['film_id' => 'id']);
    }

}
