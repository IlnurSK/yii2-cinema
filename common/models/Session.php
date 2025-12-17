<?php

namespace common\models;

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
            [['price'], 'number', 'min' => 0],
            [['film_id'], 'exist', 'skipOnError' => true, 'targetClass' => Film::class, 'targetAttribute' => ['film_id' => 'id']],
            // Валидатор временного интервала
            [['session_datetime'], 'validateTime'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'film_id' => 'Фильм',
            'session_datetime' => 'Время и дата сеанса',
            'price' => 'Стоимость',
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

    /**
     * Валидатор временного интервала (с учетом 30 минутного перерыва)
     */
    public function validateTime($attribute, $params)
    {
        // Проверяем данные по выбранному фильму
        $currentFilm = Film::findOne($this->film_id);
        if (!$currentFilm) {
            return;
        }

        // Получаем стартовую дату нового сеанса
        $newStart = strtotime($this->session_datetime);

        // Проверка на корректный ввод даты (например, пустая или кривой формат)
        if (!$newStart) {
            $this->addError($attribute, 'Неверный формат даты. Используйте календарь.');
            return;
        }

        // Вычисляем продолжительность выбранного фильма
        $durationSeconds = $currentFilm->duration * 60;
        $breakSeconds    = 30 * 60; // 30 минут перерыва

        // Получаем конечную дату нашего сеанса + перерыв
        $newEndTotal = $newStart + $durationSeconds + $breakSeconds;

        // Ищем конфликты с существующими сеансами
        // Получаем все сеансы
        $sessions = Session::find()
            ->where(['!=', 'id', (int)$this->id]) // Исключаем текущий сеанс
            ->all();

        foreach ($sessions as $existingSession) {
            // Получаем фильм уже существующего сеанса, чтобы узнать его продолжительность
            $existingFilm = Film::findOne($existingSession->film_id);
            if (!$existingFilm) {
                continue;
            }

            // Вычисляем временные рамки (аналогично) существующего сеанса
            $existingStart = strtotime($existingSession->session_datetime);
            $existingDuration = $existingFilm->duration * 60;
            $existingEndTotal = $existingStart + $existingDuration + $breakSeconds;

            // Логика пересечения временных интервалов
            if ($newStart < $existingEndTotal && $newEndTotal > $existingStart) {

                // Считаем, когда заканчивается существующий фильм (без учета перерыва)
                $movieEndTime = $existingStart + $existingDuration;

                // Форматируем время для сообщения
                $timeStart = date('H:i', $existingStart);
                $timeEnd = date('H:i', $movieEndTime);

                // Формируем сообщение об ошибке, при выявлении пересечения временных интервалов
                $this->addError($attribute, "Сеанс занят! В это время с ($timeStart) идет '{$existingFilm->title}' до ($timeEnd) (+30 мин перерыв).");

                return;
            }
        }
    }

}
