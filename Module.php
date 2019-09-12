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

    public function getHtmlCode()
    {
        $htmlCode = $this->settings->get('htmlCode');
        if (empty($htmlCode)) {
            return '';
        }
        return $htmlCode;
    }
}
