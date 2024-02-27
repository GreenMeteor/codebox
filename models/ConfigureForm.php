<?php

namespace humhub\modules\codebox\models;

use Yii;

/**
 * ConfigureForm defines the configurable fields.
 */
class ConfigureForm extends \yii\base\Model
{

    public $title;

    public $htmlCode;

    /**
     * Sort the order of the widget
     */
    public $sortOrder;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string'],
            ['htmlCode', 'string'],
            ['sortOrder', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('CodeboxModule.base', 'Title:'),
            'htmlCode' => Yii::t('CodeboxModule.base', 'Codebox HTML code snippet:'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'htmlCode' => Yii::t('CodeboxModule.base', 'e.g. <code>Code Here</code>, also for inline scripts use {code}.', ['code' => '<code>&lt;script nonce={{nonce}}&gt;</code>']),
        ];
    }

    public function loadSettings()
    {
        $module = Yii::$app->getModule('codebox');
        $settings = $module->settings;

        $this->title = $settings->get('title');
        $this->htmlCode = $settings->get('htmlCode');
        $this->sortOrder = $settings->get('sortOrder');

        return true;
    }

    public function save()
    {
        $module = Yii::$app->getModule('codebox');
        $settings = $module->settings;

        $settings->set('title', $this->title);
        $settings->set('htmlCode', $this->htmlCode);
        $settings->set('sortOrder', $this->sortOrder);

        return true;
    }

}
