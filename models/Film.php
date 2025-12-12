<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\web\UploadedFile;

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
     * @var UploadedFile Виртуальный атрибут для загрузки файла (не в БД)
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%film}}';
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
            // Правило для файла: только png и jpg, может быть пустым
            [['imageFile'],'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
            'imageFile' => 'Постер',
            'image_ext' => 'Расширение картинки',
            'description' => 'Описание',
            'duration' => 'Продолжительность (мин)',
            'age_rating' => 'Возрастные ограничения',
        ];
    }

    /**
     * Метод загрузки файла
     * @throws Exception
     */
    public function upload(): bool
    {
        // Проверка существования файла
        if ($this->imageFile) {
            $path = Yii::getAlias('@webroot/upload/film/');
            
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
     * Gets query for [[Sessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::class, ['film_id' => 'id']);
    }

}
