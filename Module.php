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
     * Overrides the default getConfigUrl() method to open a modal instead.
     * 
     * @return string JavaScript code to open the modal
     */
    public function getConfigUrl()
    {
        return Url::to(['/codebox/admin/index']);
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
            $settings[] = [
                'title' => $model->title,
                'htmlCode' => $model->htmlCode,
                'sortOrder' => $model->sortOrder,
            ];
        }

        return $settings ?: [];
    }

    /**
     * Retrieves a specific setting from the database.
     * 
     * @param string $name the name of the setting attribute
     * @param mixed $defaultValue the default value if the setting is not found
     * @return mixed the value of the setting
     */
    protected function getSetting($name, $defaultValue = null)
    {
        $settings = $this->getSettings();

        if (!empty($settings) && isset($settings[0][$name])) {
            return $settings[0][$name];
        }

        return Yii::$app->settings->get('codebox.' . $name, $defaultValue);
    }

    /**
     * Retrieves the title from the module settings.
     * 
     * @return string|null the title
     */
    public function getTitle()
    {
        return $this->getSetting('title');
    }

    /**
     * Retrieves the HTML code snippet from the module settings.
     * 
     * @return string|null the HTML code snippet
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
        return (int) $this->getSetting('sortOrder', 100);
    }
}