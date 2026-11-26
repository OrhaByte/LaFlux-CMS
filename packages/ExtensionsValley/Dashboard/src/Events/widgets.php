<?php

namespace ExtensionsValley\Dashboard\Events;

\Event::listen('admin.widgets.groups', function ($collection) {
    $collection->put('dashboard', [
        'items' => [
            [
                'order' => '1',
                'col' => '12',
                'layout' => 'Dashboard::dashboard.widgets.tiles',
            ],
            [
                'order' => '2',
                'col' => '12',
                'layout' => 'Dashboard::dashboard.widgets.activities',
            ],
        ],
    ]);
});
