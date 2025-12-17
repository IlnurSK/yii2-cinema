<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * Модель для таблицы "film".
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
class Film extends ActiveRecord
{
    /**
     * @var UploadedFile|null Загружаемый файл изображения
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
            [['title', 'duration', 'age_rating'], 'required'],
            [['description'], 'string'],
            [['duration'], 'integer'],
            [['title', 'image_ext', 'age_rating'], 'string', 'max' => 255],
            [['image_ext', 'description'], 'default', 'value' => null],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'title'       => 'Название фильма',
            'image_ext'   => 'Расширение файла',
            'description' => 'Описание',
            'duration'    => 'Продолжительность (мин)',
            'age_rating'  => 'Возрастное ограничение',
            'imageFile'   => 'Постер',
        ];
    }

    /**
     * Связь с сеансами.
     * @return ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::class, ['film_id' => 'id']);
    }

    /**
     * Список доступных возрастных ограничений.
     * @return array
     */
    public static function getAgeRatings()
    {
        return [
            '0+'  => '0+',
            '6+'  => '6+',
            '12+' => '12+',
            '16+' => '16+',
            '18+' => '18+',
        ];
    }

    /**
     * Получение URL картинки для отображения.
     * @return string|null
     */
    public function getImageUrl()
    {
        if ($this->image_ext) {
            return '/uploads/film/' . $this->id . '.' . $this->image_ext;
        }
        return null;
    }

    /**
     * Загрузка файла на сервер.
     * Создает папку, если она не существует, и сохраняет файл.
     *
     * @return bool
     * @throws ServerErrorHttpException Если не удалось создать директорию
     */
    public function upload(): bool
    {
        if ($this->imageFile) {
            $dir = Yii::getAlias('@frontend/web/uploads/film/');

            // Создаем директорию, если её нет
            if (!file_exists($dir)) {
                if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new ServerErrorHttpException('Не удалось создать директорию для загрузки: ' . $dir);
                }
            }

            $this->image_ext = $this->imageFile->extension;
            $fullPath = $dir . $this->id . '.' . $this->image_ext;

            // Сохраняем файл
            return $this->imageFile->saveAs($fullPath);
        }
        return false;
    }

    /**
     * Физическое удаление файла с диска.
     *
     * @param string|null $ext Если передано, удаляет файл с конкретным расширением.
     */
    private function deleteImage($ext = null)
    {
        $extension = $ext ?: $this->image_ext;

        if ($extension) {
            $path = Yii::getAlias('@frontend/web/uploads/film/') . $this->id . '.' . $extension;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Действия перед сохранением записи.
     * Удаляет старый файл, если загружен новый с другим расширением.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Если это не вставка (update) и загружен новый файл
            if (!$insert && $this->imageFile) {
                $oldExt = $this->getOldAttribute('image_ext');

                // Если расширение изменилось (например, было png, стало jpg), удаляем старый файл.
                // Если расширение то же самое, saveAs() просто перезапишет файл.
                if ($oldExt && $oldExt !== $this->imageFile->extension) {
                    $this->deleteImage($oldExt);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Действия после удаления записи.
     * Удаляет связанное изображение.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
    }
}