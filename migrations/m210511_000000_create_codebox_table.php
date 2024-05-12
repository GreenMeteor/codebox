<?php

use yii\db\Migration;

/**
 * Handles the creation of table `codebox`.
 */
class m210511_000000_create_codebox_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('codebox', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'htmlCode' => $this->text(),
            'sortOrder' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('codebox');
    }
}
