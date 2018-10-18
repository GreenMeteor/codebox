<?php

namespace humhub\modules\codebox;

return [
    'id' => 'codebox',
    'class' => 'humhub\modules\codebox\Module',
    'namespace' => 'humhub\modules\codebox',
    'events' => [
        [
            'class' => \humhub\modules\dashboard\widgets\Sidebar::class,
            'event' => \humhub\modules\dashboard\widgets\Sidebar::EVENT_INIT,
            'callback' => [
                'humhub\modules\codebox\Events',
                'addCodeboxFrame'
            ]
        ],
        [
            'class' => \humhub\modules\admin\widgets\AdminMenu::class,
            'event' => \humhub\modules\admin\widgets\AdminMenu::EVENT_INIT,
            'callback' => [
                'humhub\modules\codebox\Events',
                'onAdminMenuInit'
            ]
        ]
    ]
];
?>
