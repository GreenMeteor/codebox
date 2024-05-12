<?php

use yii\db\Migration;

/**
 * Class m240512_000001_remove_old_codebox_settings
 */
class m240512_000001_remove_old_codebox_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Delete 'codebox' settings from  'setting' table
        $this->delete('setting', ['module_id' => 'codebox']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // No need for down migration as this migration only performs data deletion
        return true;
    }
}