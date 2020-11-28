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
            ['sortOrder', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'htmlCode' => Yii::t('CodeboxModule.base', 'Codebox HTML code snippet:'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'htmlCode' => Yii::t('CodeboxModule.base', 'e.g. <code><php? ?></code>'),
        ];
    }

    public function loadSettings()
    {
        $this->title = Yii::$app->getModule('codebox')->settings->get('title');
        $this->htmlCode = Yii::$app->getModule('codebox')->settings->get('htmlCode');
        $this->sortOrder = Yii::$app->getModule('codebox')->settings->get('sortOrder');

        return true;
    }

    public function save()
    {
        Yii::$app->getModule('codebox')->settings->set('title', $this->title);
        Yii::$app->getModule('codebox')->settings->set('htmlCode', $this->htmlCode);
        Yii::$app->getModule('codebox')->settings->set('sortOrder', $this->sortOrder);

        return true;
    }

}
