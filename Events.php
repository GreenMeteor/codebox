<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;
use yii\base\BaseObject;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\admin\permissions\ManageModules;

class Events extends BaseObject
{

    public static function onAdminMenuInit($event)
    {
        if (!Yii::$app->user->can(ManageModules::class)) {
            return;
        }

        /** @var AdminMenu $menu */
        $menu = $event->sender;

        $menu->addEntry(new MenuLink([
            'label' => Yii::t('CodeboxModule.base', 'Codebox Settings'),
            'url' => Url::toRoute('/codebox/admin/index'),
            'icon' => Icon::get('code'),
            'isActive' => Yii::$app->controller->module && Yii::$app->controller->module->id == 'codebox' && Yii::$app->controller->id == 'admin',
            'sortOrder' => 600,
            'isVisible' => true,
        ]));
    }

    public static function addCodeboxFrame($event)
    {
        $module = Yii::$app->getModule('codebox');
        $settings = $module->settings;

        if (Yii::$app->user->isGuest) {
            return;
        } else {
            Yii::$app->user;
        }

        $event->sender->addWidget(widgets\CodeboxFrame::class, [], ['sortOrder' => $settings->get('sortOrder')]);
    }
}
