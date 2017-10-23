<?php
return [
    'name' => 'Меч Фемиды',
    'title' => 'Меч Фемиды',
    'menu' => [
        'contacts' => [
            'name' => 'Контакты',
            'icons' => 'fa fa-th-large',
            'url' => '/contacts',
            'baseModel' => new App\Contact,
        ],
        'leads' => [
            'name' => 'Сделки',
            'icons' => 'fa fa-diamond',
            'url' => '/leads',
            'baseModel' => new App\Lead()
        ],
        'tasks' => [
            'name' => 'Задачи',
            'icons' => 'fa fa-tasks',
            'url' => '/tasks',
            'baseModel' =>  new \App\Task()
        ],
        'messages' => [
            'name' => 'Рассылка',
            'icons' => 'fa fa-paper-plane',
            'url' => '/messages',
            'baseModel' => new \App\Message()
        ],
        'analytics' => [
            'name' => 'Аналитика',
            'icons' => 'fa fa-area-chart',
            'url' => '/analytics',
            'baseModel' => new App\Department(),
        ],
        'expenses' => [
            'name' => 'Расходы',
            'icons' => 'fa fa-rub ',
            'url' => '/expenses',
            'baseModel' => new \App\Expense()
        ],
        'settings' => [
            'name' => 'Настройки',
            'icons' => 'fa fa-tasks',
            'url' => '/settings',
            'baseModel' => new App\LeadService(), // @todo fix
        ],
    ]
];
