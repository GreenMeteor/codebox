<?php

namespace humhub\modules\codebox\widgets;

use Yii;
use humhub\components\Widget;
use humhub\modules\web\security\helpers\Security;

/**
 * CodeboxFrame adds HTML snippet code to all layouts extended by config.php
 */
class CodeboxFrame extends Widget
{

    public $contentContainer;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $module = Yii::$app->getModule('codebox');

        $title = $module->getTitle();

        $sortOrder = $module->getOrder();

        $htmlCode = $module->getHtmlCode();

        if (!$title || !$htmlCode || !$sortOrder) {
            return '';
        }

        return $this->render('codeboxframe', ['title' => $title, 'htmlCode' => $htmlCode, $sortOrder => 'sortOrder', 'nonce' => Security::getNonce()]);
    }

}
