<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;
use yii\base\BaseObject;
use humhub\models\Setting;
use humhub\modules\ui\icon\widgets\Icon;

class Events extends BaseObject
{

    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => Yii::t('CodeboxModule.base', 'Codebox Settings'),
            'url' => Url::toRoute('/codebox/admin/index'),
            'group' => 'settings',
            'icon' => Icon::get('code'),
            'isActive' => Yii::$app->controller->module && Yii::$app->controller->module->id == 'codebox' && Yii::$app->controller->id == 'admin',
            'sortOrder' => 650,
        ]);
    }

    public static function addCodeboxFrame($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        } else {
            Yii::$app->user;
        }

        $event->sender->addWidget(widgets\CodeboxFrame::class, [], ['sortOrder' => 100]);
    }
}
