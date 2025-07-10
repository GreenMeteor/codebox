<?php

use yii\db\Migration;

class m219304_938273_add_codetype_to_codebox extends Migration
{
    public function up()
    {
        $this->addColumn('codebox', 'codeType', 'string(10) DEFAULT "html"');
    }

    public function down()
    {
        $this->dropColumn('codebox', 'codeType');
    }
}