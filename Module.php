<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
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
        return $htmlCode;
    }
}
