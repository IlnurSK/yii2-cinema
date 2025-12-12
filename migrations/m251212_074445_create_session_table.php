<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class m251212_074445_create_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->primaryKey(),
            'film_id' => $this->integer()->notNull(),
            'session_datetime' => $this->dateTime()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-session-film_id',
            '{{%session}}',
            'film_id',
            '{{%film}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-session-film_id', '{{%session}}');
        $this->dropTable('{{%session}}');
    }
}
