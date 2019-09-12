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

    /**
     * @inheritdoc
     */
    public function loadSettings()
    {
        $this->htmlCode = Yii::$app->getModule('codebox')->settings->get('htmlCode');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        Yii::$app->getModule('codebox')->settings->set('htmlCode', $this->htmlCode);
        return true;
    }

}
