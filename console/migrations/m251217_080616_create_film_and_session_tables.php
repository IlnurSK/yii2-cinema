<?php

use yii\db\Migration;

class m251217_080616_create_film_and_session_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%film}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'image_ext' => $this->string(),
            'description' => $this->text(),
            'duration' => $this->integer()->notNull(),
            'age_rating' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%session}}', [
            'id' => $this->primaryKey(),
            'film_id' => $this->integer()->notNull(),
            'session_datetime' => $this->dateTime()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-session-film_id',
            '{{%session}}',
            'film_id'
        );

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
        $this->dropIndex('idx-session-film_id', '{{%session}}');
        $this->dropTable('{{%session}}');
        $this->dropTable('{{%film}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251217_080616_create_film_and_session_tables cannot be reverted.\n";

        return false;
    }
    */
}
