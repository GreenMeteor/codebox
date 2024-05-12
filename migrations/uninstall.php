<?php

use yii\db\Migration;

/**
 * Class uninstall
 */
class uninstall extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop the codebox table
        $this->dropTable('codebox');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // This method should not be used as it might cause data loss.
        return false;
    }
}