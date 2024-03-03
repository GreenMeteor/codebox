<?php

namespace humhub\modules\codebox;

use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\dashboard\widgets\Sidebar;

return [
    'id' => 'codebox',
    'class' => 'humhub\modules\codebox\Module',
    'namespace' => 'humhub\modules\codebox',
    'events' => [
        ['class' => Sidebar::class, 'event' => Sidebar::EVENT_INIT, 'callback' => ['humhub\modules\codebox\Events', 'addCodeboxFrame']],
        ['class' => AdminMenu::class, 'event' => AdminMenu::EVENT_INIT, 'callback' => ['humhub\modules\codebox\Events', 'onAdminMenuInit']]
    ]
];
