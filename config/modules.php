<?php

$modules = [
  /**
   * Список модулей, используемых основным приложением
   * Модулт отдельных приложений объявляются в своих файлах (api, partner, ruvents)
   */
    'catalog',
    'commission',
    'company',
    'contact',
    'event',
    'education',
    'geo',
    'geo2',
    'main',
    'news',
    'oauth' => ['csrfValidation' => true],
    'pay' => ['csrfValidation' => true],
    'rbac',
    'tag',
    'user',
    'search',
    'job',
    'page',
    'widget',
    'link',
    'raec',
    'sms',
    'competence',

  /** Технические модули */
    'mytest',
    'mail'
];

if (YII_DEBUG) {
    $modules['gii'] = [
        'class' => 'system.gii.GiiModule',
        'password' => '123456'
    ];
}
return $modules;
