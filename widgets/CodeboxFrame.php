<?php

namespace humhub\modules\codebox\widgets;

use Yii;

/**
 * CodeboxFrame adds HTML snippet code to all layouts extended by config.php
 */
class CodeboxFrame extends \humhub\components\Widget
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        return Yii::$app->settings->get('htmlCode');
    }

}
