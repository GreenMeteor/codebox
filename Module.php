<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;
use humhub\components\Module as BaseModule;
use humhub\modules\codebox\models\ConfigureForm;

class Module extends BaseModule
{
    public $resourcesPath = 'resources';

    /**
     * Returns the URL to the configuration page.
     * 
     * @return string the URL to the configuration page
     */
    public function getConfigUrl()
    {
        return Url::to(['/codebox/admin']);
    }

    /**
     * Retrieves all settings from the database.
     * 
     * @return array the settings
     */
    protected function getSettings()
    {
        $models = ConfigureForm::find()->all();
        $settings = [];

        foreach ($models as $model) {
            $settings['title'] = $model->title;
            $settings['htmlCode'] = $model->htmlCode;
            $settings['sortOrder'] = $model->sortOrder;
        }
        return $settings;
    }

    /**
     * Retrieves a setting from the database.
     * 
     * @param string $name the name of the setting attribute
     * @param mixed $defaultValue the default value if the setting is not found
     * @return mixed the value of the setting
     */
    protected function getSetting($name, $defaultValue = null)
    {
        $settings = $this->getSettings();

        return isset($settings[$name]) ? $settings[$name] : $defaultValue;
    }

    /**
     * Retrieves the title from the module settings.
     * 
     * @return string the title
     */
    public function getTitle()
    {
        return $this->getSetting('title');
    }

    /**
     * Retrieves the HTML code snippet from the module settings.
     * 
     * @return string the HTML code snippet
     */
    public function getHtmlCode()
    {
        return $this->getSetting('htmlCode');
    }

    /**
     * Retrieves the sort order from the module settings.
     * 
     * @return int the sort order
     */
    public function getOrder()
    {
        return $this->getSetting('sortOrder', 100);
    }
}
