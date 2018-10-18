<?php

namespace humhub\modules\codebox\models;

use Yii;

class ConfigureForm extends \yii\base\Model
{

    public $htmlCode;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $settingsManager = Yii::$app->settings;
        $this->htmlCode = $settingsManager->get('htmlCode');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['htmlCode', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'htmlCode' => Yii::t('CodeboxModule.base', 'HTML snippet code.'),
        ];
    }

    public function loadSettings()
    {
        $this->htmlCode = Yii::$app->getModule('codebox')->settings->get('htmlCode');
        return true;
    }

    /**
     * Saves the form
     *
     * @return boolean
     */
    public function save()
    {
        $settingsManager = Yii::$app->settings;
        $settingsManager->set('htmlCode', $this->htmlCode);

        return true;
    }

}
