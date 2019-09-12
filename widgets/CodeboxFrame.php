<?php

namespace humhub\modules\codebox\widgets;

use Yii;

/**
 * CodeboxFrame adds HTML snippet code to all layouts extended by config.php
 */
class CodeboxFrame extends \humhub\components\Widget
{


    public $contentContainer;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $htmlCode = Yii::$app->getModule('codebox')->getHtmlCode();
        return $this->render('codeboxframe', ['htmlCode' => $htmlCode]);
    }

}
