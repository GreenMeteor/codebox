<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;
use humhub\libs\Html;
use humhub\components\Module as BaseModule;

class Module extends BaseModule
{

    public $resourcesPath = 'resources';

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/codebox/admin']);
    }

    public function getTitle()
    {
        $title = $this->settings->get('title');
        if (empty($title)) {
            return '';
        }
        return $title;
    }

    public function getHtmlCode()
    {
        $htmlCode = $this->settings->get('htmlCode');

        if (empty($htmlCode)) {
            return '';
        }

        $nonce = Html::nonce();
        $htmlNonce = preg_replace('/<script([^>]*)>/', '<script$1 ' . $nonce . '>', $htmlCode);

        return $htmlNonce;
    }


    public function getOrder()
    {
        $sortOrder = $this->settings->get('sortOrder');
        if (empty($sortOrder)) {
            return '100';
        }
        return $sortOrder;
    }
}
