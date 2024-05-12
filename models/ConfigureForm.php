<?php

namespace humhub\modules\codebox\models;

use Yii;
use humhub\components\ActiveRecord;

/**
 * Codebox ActiveRecord model.
 *
 * @property int $id
 * @property string $title
 * @property string $htmlCode
 * @property int $sortOrder
 */
class ConfigureForm extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codebox';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'htmlCode'], 'string'],
            ['sortOrder', 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('CodeboxModule.base', 'ID'),
            'title' => Yii::t('CodeboxModule.base', 'Title:'),
            'htmlCode' => Yii::t('CodeboxModule.base', 'Codebox HTML code snippet:'),
            'sortOrder' => Yii::t('CodeboxModule.base', 'Sort Order'),
        ];
    }
}