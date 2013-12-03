<?php
return array(
  'modules' => array(
    /**
     * Список модулей, используемых основным приложением
     * Модулт отдельных приложений объявляются в своих файлах (api, partner, ruvents)
     */
    'catalog',
    'commission',
    'company',
    'contact',
    'event',
    'geo',
    'main',
    'news',
    'oauth',
    'pay',
    'rbac',
    'tag',
    'user',
    'search',
    'job',
    'page',  

    'competence',
      
    /** Технические модули */
    'convert',
    'mytest',
    'mail'
  ),

  'csrfValidationModules' => array(
    'main',
    'oauth',
    'pay'
  )
);
