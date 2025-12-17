<?php

namespace common\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "film".
 *
 * @property int $id
 * @property string $title
 * @property string|null $image_ext
 * @property string|null $description
 * @property int $duration
 * @property string $age_rating
 *
 * @property Session[] $sessions
 */
class Film extends \yii\db\ActiveRecord
{
    /**
     * Виртуальный атрибут для загрузки файла (не в БД)
     */
    public $imageFile;


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
            [['image_ext', 'description'], 'default', 'value' => null],
            [['title', 'duration', 'age_rating'], 'required'],
            [['description'], 'string'],
            [['duration'], 'integer'],
            [['title', 'image_ext', 'age_rating'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название фильма',
            'image_ext' => 'Расширение картинки',
            'description' => 'Описание',
            'duration' => 'Продолжительность (мин)',
            'age_rating' => 'Возрастное ограничение',
            'imageFile' => 'Постер',
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

    /**
     * Выпадающий список для возрастного ограничения
     * @return string[]
     */
    public static function getAgeRatings()
    {
        return [
            '0+' => '0+',
            '6+' => '6+',
            '12+' => '12+',
            '16+' => '16+',
            '18+' => '18+',
        ];
    }

    /**
     * Вспомогательный метод для получения ссылки на картинку
     */
    public function getImageUrl()
    {
        if ($this->image_ext) {
            return '/upload/film/' . $this->id . "." . $this->image_ext;
        }
        return null; // Путь к заглушке no-image.jpg
    }

    /**
     * Метод загрузки файла
     * @throws Exception
     */
    public function upload(): bool
    {
        // Проверка существования файла
        if ($this->validate() && $this->imageFile) {
            $path = Yii::getAlias('@frontend/web/upload/film/');

            // Если папки нет, создадим её
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // Формируем имя файла: ID.расширение (id.jpg)
            $filename = $this->id . "." . $this->imageFile->extension;

            // Сохраняем файл на диск
            $this->imageFile->saveAs($path . $filename);

            // Обновляем запись в БД (сохраняем расширение)
            $this->image_ext = $this->imageFile->extension;
            $this->save(false); // сохраняем без повторной валидации

            return true;
        }
        return false;
    }

    /**
     * Метод удаления картинки
     * @return void
     */
    private function deleteImage()
    {
        $file = Yii::getAlias('@frontend/web/uploads/film/') . $this->id . '.' . $this->image_ext;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Метод удаления файла при удалении записи
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
    }

    /**
     * Метод удаления файла при обновлении записи
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            // Проверка, что это ОБНОВЛЕНИЕ записи и загружен НОВЫЙ файл
            if (!$insert && $this->imageFile && $this->image_ext) {
                $this->deleteImage();
            }
            return true;
        }
        return false;
    }

}
