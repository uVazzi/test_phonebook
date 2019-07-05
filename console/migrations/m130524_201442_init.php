<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        // Таблица пользователей
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'surname' => $this->string(50)->notNull(),
            'patronymic' => $this->string(50)->notNull(),
            'date_birth' => $this->string(10)->notNull(),
            'create_at' => $this->integer()->notNull(),
            'update_at' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
        ], $tableOptions);
        // Таблица номеров телефона
        $this->createTable('phone', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'number' => $this->string(20)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'create_at' => $this->integer()->notNull(),
            'update_at' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
        ]);
        // Внешний ключ для таблицы phone
        $this->addForeignKey('fk__phone__user_id__to__user__id',
            '{{%phone}}', 'user_id',
            '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk__phone__user_id__to__user__id', '{{%phone}}');
        $this->dropTable('{{%phone}}');
        $this->dropTable('{{%user}}');
    }
}
