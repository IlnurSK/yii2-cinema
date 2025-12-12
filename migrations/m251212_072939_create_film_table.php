<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%film}}`.
 */
class m251212_072939_create_film_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%film}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Название фильма'),
            'image_ext' => $this->string(10)->comment('Расширение картинки'),
            'description' => $this->text()->comment('Описание'),
            'duration' => $this->integer()->notNull()->comment('Продолжительность'),
            'age_rating' => $this->string(10)->comment('Возрастные ограничения'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%film}}');
    }
}
