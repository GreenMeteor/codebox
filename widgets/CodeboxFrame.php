<?php

namespace humhub\modules\codebox\widgets;

use Yii;
use humhub\libs\Html;
use humhub\components\Widget;

/**
 * CodeboxFrame adds HTML snippet code to all layouts extended by config.php
 */
class CodeboxFrame extends Widget
{
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

        // Generate nonce attribute
        $nonce = Html::nonce();

        // Check if {{nonce}} placeholder exists in htmlCode
        if (strpos($htmlCode, 'nonce={{nonce}}') !== false) {
            // Replace {{nonce}} with the generated nonce value
            $htmlCode = str_replace('nonce={{nonce}}', $nonce, $htmlCode);
        }

        return $this->render('codeboxframe', ['title' => $title, 'htmlCode' => $htmlCode, 'sortOrder' => $sortOrder, 'nonce' => $nonce]);
    }

}
