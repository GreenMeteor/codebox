<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;
use yii\base\BaseObject;
use humhub\models\Setting;
use humhub\modules\codebox\Assets;
use humhub\modules\codebox\widgets\CodeboxFrame;

class Events extends BaseObject
{

    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => Yii::t('CodeboxModule.base', 'Codebox Settings'),
            'url' => Url::toRoute('/codebox/admin/index'),
            'group' => 'settings',
            'icon' => '<i class="fa fa-code"></i>',
            'isActive' => Yii::$app->controller->module && Yii::$app->controller->module->id == 'codebox' && Yii::$app->controller->id == 'admin',
            'sortOrder' => 650
        ]);
    }

public static function addCodeboxFrame($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        $event->sender->view->registerAssetBundle(Assets::class);
        $event->sender->addWidget(CodeboxFrame::class, [], [
            'sortOrder' => Setting::Get('timeout', 'codebox')
        ]);
    }
}
